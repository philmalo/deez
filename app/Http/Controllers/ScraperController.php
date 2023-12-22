<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Bouteille;
use Illuminate\Support\Facades\DB;

class ScraperController extends Controller
{

    protected $crawler;
    protected $client;

    public function __construct(){

        $this->crawler = new Crawler();
        $this->client = new Client();
    }

    /**
     * récupérateur de codes SAQ sur le catalogue de bouteilles de vins de saq.com
     */
    public function codes () {

        //* on récupère le nombre total de pages avec 96 bouteilles par page
        $totalPages = $this->getNombreTotalPages();

        //* récupération des codes de la SAQ déjà dans la base de données
        $codesExistants = [];
        if (DB::table('bouteilles')->count() > 0) {

            $codesExistants = DB::table('bouteilles')->pluck('code_SAQ')->toArray();
        }

        //* instanciation de la variable contenant tous les codes reçus au fil des pages du catalogue, sert à comparer les codes reçus à ceux de la BD pour désactiver les bouteilles non présentes dans le catalogue
        $codes = [];

        for($page = 1; $page <= $totalPages; $page++){

            $reponse = $this->client->get("https://www.saq.com/fr/produits/vin?p=$page&product_list_limit=96&product_list_order=name_asc");
            $htmlCodes = $reponse->getBody()->getContents();
            $this->crawler->clear();
            $this->crawler->addHtmlContent($htmlCodes);

            $pageCodes = $this->crawler->filter('.saq-code')->each(function($code){

                //* récupération d'une liste de tous les codes contenus dans la page, traités, prêts à être utilisés
                return preg_replace('/[^0-9]/', '', $code->text());
            });

            //* fusion de la liste des codes récupérés à chaque page, pour comparer avec les codes existants dans la base de données à la fin, pour définir les bouteilles désactivées
            $codes = array_merge($codes, $pageCodes);

            foreach($pageCodes as $code){

                if(!in_array($code, $codesExistants)){

                    //* insertion nouvelle bouteille
                    $bouteille = new Bouteille();
                    $bouteille->code_SAQ = $code;
                    $bouteille->save();
                    //* ajout du code_SAQ de la nouvelle bouteille dans la variable codesExistants, on s'assure de jamais avoir de doublons car on compare le code suivant avec la liste des codes de la BDgs à jour
                    $codesExistants[] = $code;
                }
            }
        }

        //* réinitialisation de la colonne "existe_plus" afin de n'avoir que les codes qui ne sont pas présents pour de vrai sans avoir d'accumulation de codes inexistants
        Bouteille::query()->update(['existe_plus' => false]);

        //* vérification des codes existants contre les codes récupérés des pages de la saq, on change le statut de existe_plus ici
        Bouteille::whereNotIn('code_SAQ', $codes)->update(['existe_plus' => true]);

        //* récupération d'informations pour l'Affichage de détails du traitement des données
        $totalBouteilles = Bouteille::all()->count();
        $bouteillesDesactivees = Bouteille::where('existe_plus', true)->count();
        $bouteillesAjoutees = Bouteille::where('est_scrapee', false)->count();

        return view('scraper.codes', ['mot' => 'Procédure complétée',
                                    'bouteillesAjoutees' => $bouteillesAjoutees,
                                    'totalBouteilles' => $totalBouteilles,
                                    'bouteillesDesactivees' => $bouteillesDesactivees]);
    }

    /**
     * fonction de récupération des informations détaillées de chaques nouvelles bouteilles dans la base de données
     */
    public function liste () {
        // limite de temps à 0 pour la désactiver et ainsi potentiellement éliminer les erreurs de timeout
        set_time_limit(0);
        $erreurs = [];
        // variable pour pouvoir afficher le code de la bouteille qui aurait déclenché une erreur dans le catch
        $codeSAQ = "";

        try{

            $bouteillesNonScrapees = Bouteille::where('est_scrapee', false)->get();

            // récupération des codes SAQ des bouteilles traitées, afin de les afficher dans la vue du résultat du scraper
            $listeCodesTraites = [];

            if(!$bouteillesNonScrapees->isEmpty()){
                foreach ($bouteillesNonScrapees as $bouteille) {
                    $codeSAQ = $bouteille->code_SAQ;

                    //TODO SECTION PAGES FRANÇAISES
                    //* Fabrication de l'url à scraper
                    $url_fr = 'https://www.saq.com/fr/' . $bouteille->code_SAQ;

                    //* Requête de la page
                    $reponse_fr = $this->client->request('GET', $url_fr);

                    //* Stockage de l'information de la page
                    $html_fr = $reponse_fr->getBody()->getContents();

                    //? On vide le crawler par précaution et on y ajoute la page fr
                    $this->crawler->clear();
                    $this->crawler->addHtmlContent($html_fr);

                    //* Cibler le nom de la bouteille
                    $titre_fr = $this->crawler->filter('h1')->first()->text();

                    //* Cibler le texte descriptif, si présent
                    $texte_fr = $this->getTextFromCrawler('.wrapper-content-info');

                    //* les tableaux d'attributs
                    $listeAttributs_fr = $this->crawler->filter('ul.list-attributs li');
                    $attributs_fr = $this->extractInformation($listeAttributs_fr, "fr", false, true);

                    //* les tableaux de tasting
                    $listeTasting_fr = $this->crawler->filter('ul.tasting-container li');
                    $tasting_fr = $this->extractInformation($listeTasting_fr, "fr", true, false);

                    //* les images
                    $image_fr = $this->extractImageInformation('.MagicToolboxContainer img', 'images');

                    //* les pastilles
                    $pastille_fr = $this->extractImageInformation('.wrapper-taste-tag img', 'pastilles', true);

                    //* le prix
                    $prix = null;
                    $prix = $this->crawler->filter('.price')->first()->text();


                    //TODO INSERTION DES DONNÉES FRANÇAISES
                    //* SECTION TASTING
                    $bouteille->aromes_fr = $tasting_fr["Arômes"];
                    $bouteille->acidite_fr = $tasting_fr["Acidité"];
                    $bouteille->sucrosite_fr = $tasting_fr["Sucrosité"];
                    $bouteille->corps_fr = $tasting_fr["Corps"];
                    $bouteille->bouche_fr = $tasting_fr["Bouche"];
                    $bouteille->bois_fr = $tasting_fr["Bois"];
                    $bouteille->temperature_fr = $tasting_fr["Température de service"];
                    $bouteille->millesime = $tasting_fr["Millésime dégusté"];
                    $bouteille->potentiel_de_garde_fr = $tasting_fr["Potentiel de garde"];

                    //* SECTION ATTRIBUTS
                    $bouteille->pays_fr = $attributs_fr["Pays"];
                    $bouteille->region_fr = $attributs_fr["Région"];
                    $bouteille->designation_reglementee_fr = $attributs_fr["Désignation réglementée"];
                    $bouteille->classification_fr = $attributs_fr["Classification"] ?? null;
                    if (isset($attributs_fr["Cépage"])) {
                        $bouteille->cepage = $attributs_fr["Cépage"] ?? null;
                    } 
                    elseif (isset($attributs_fr["Cépages"])) {
                        $bouteille->cepage = $attributs_fr["Cépages"] ?? null;
                    }
                    $bouteille->degree_alcool = $attributs_fr["Degré d'alcool"];
                    $bouteille->taux_de_sucre = $attributs_fr["Taux de sucre"];
                    $bouteille->couleur_fr = $attributs_fr["Couleur"];
                    $bouteille->format = $attributs_fr["Format"];
                    $bouteille->producteur = $attributs_fr["Producteur"];
                    $bouteille->agent_promotionnel = $attributs_fr["Agent promotionnel"];
                    $bouteille->code_CUP = $attributs_fr["Code CUP"];
                    $bouteille->produit_quebec_fr = $attributs_fr["Produit du Québec"];
                    $bouteille->particularite_fr = $attributs_fr["Particularité"];
                    $bouteille->appellation_origine = $attributs_fr["Appellation d'origine"];

                    //* DONNÉES SÉPARÉES
                    $bouteille->nom = $titre_fr;
                    $bouteille->image_bouteille = $image_fr["url"];
                    $bouteille->image_bouteille_alt = $image_fr["alt"] ?? null;
                    $bouteille->prix = $prix;
                    $bouteille->image_pastille = $pastille_fr["url"] ?? null;
                    $bouteille->image_pastille_alt = $pastille_fr["alt"] ?? null;
                    $bouteille->description_fr = $texte_fr;


                    //TODO SECTION PAGES ANGLAISES
                    //* Fabrication de l'url à scraper
                    $url_en = 'https://www.saq.com/en/' . $bouteille->code_SAQ;

                    //* Requête de la page
                    $reponse_en = $this->client->request('GET', $url_en);

                    //* Stockage de l'information de la page
                    $html_en = $reponse_en->getBody()->getContents();

                    //? nouvelle page(en) alors on vide le crawler par précaution pour y ajouter la page anglaise
                    $this->crawler->clear();
                    $this->crawler->addHtmlContent($html_en);

                    //* Cibler le texte descriptif, si présent
                    $texte_en = $this->getTextFromCrawler('.wrapper-content-info');

                    //* les tableaux d'attributs
                    $listeAttributs_en = $this->crawler->filter('ul.list-attributs li');
                    $attributs_en = $this->extractInformation($listeAttributs_en, "en", false, true);

                    //* les tableaux de tasting
                    $listeTasting_en = $this->crawler->filter('ul.tasting-container li');
                    $tasting_en = $this->extractInformation($listeTasting_en, "en", true, false);

                    //* les pastilles
                    //? conservé en postérité, au cas où. On a décidé de traduire le texte français si on se rendait à faire le site bilingue vu que c'est toujours la même chose
                    // $pastille_en = $this->extractImageInformation('.wrapper-taste-tag img', 'pastilles', true);


                    //TODO INSERTION DES DONNÉES ANGLAISES
                    //* SECTION TASTING
                    $bouteille->aromes_en = $tasting_en["Aromas"];
                    $bouteille->acidite_en = $tasting_en["Acidity"];
                    $bouteille->sucrosite_en = $tasting_en["Sweetness"];
                    $bouteille->corps_en = $tasting_en["Body"];
                    $bouteille->bouche_en = $tasting_en["Mouthfeel"];
                    $bouteille->bois_en = $tasting_en["Wood"];
                    $bouteille->temperature_en = $tasting_en["Serving temperature"];
                    $bouteille->potentiel_de_garde_en = $tasting_en["Aging potential"];

                    //* SECTION ATTRIBUTS
                    $bouteille->pays_en = $attributs_en["Country"];
                    $bouteille->region_en = $attributs_en["Region"];
                    $bouteille->designation_reglementee_en = $attributs_en["Regulated Designation"];
                    $bouteille->classification_en = $attributs_en["Classification"] ?? null;
                    $bouteille->couleur_en = $attributs_en["Color"];
                    $bouteille->produit_quebec_en = $attributs_en["Product of Québec"];
                    $bouteille->particularite_en = $attributs_en["Special feature"];

                    //* DONNÉES SÉPARÉES
                    $bouteille->description_en = $texte_en;


                    //* sauvegarde de la bouteille et application du booléen est_scrapee à true
                    $bouteille->est_scrapee = true;
                    $bouteille->save();
                    $listeCodesTraites[] = $codeSAQ;

                    //* Une petite pause après beaucoup d'effort!
                    sleep(1);
                }
            }
        }
        catch(\Exception $e){

            $erreurs[] = "Erreur lors du traitement du code SAQ $codeSAQ : " . $e->getMessage();
            Log::error("Erreur lors du traitement du code SAQ $codeSAQ : " . $e->getMessage());
        }

        //* récupération des bouteilles à afficher selon la liste qu'on a récupéré
        $afficherBouteilles = Bouteille::whereIn('code_SAQ', $listeCodesTraites)->get();
        $totalBouteilles = Bouteille::count();

        return view('scraper.liste', [
            'bouteilles' => $afficherBouteilles,
            'totalBouteilles' => $totalBouteilles,
            'lang' => "fr",
            'erreurs' => $erreurs,
            'codesTraites' => $bouteillesNonScrapees->count(),
        ]);
    }

        /**
         * Récupérateur de texte de description
         * @param $selecteur la classe à cibler par le crawler
         * @return $texte le texte trouvé dans la classe ciblée, null le cas échéant
         */
        function getTextFromCrawler($selecteur) {

            $texte = $this->crawler->filter($selecteur)->first();

            if ($texte->count() > 0) {

                return $texte->text();
            }

            else {

                return null;
            }
        }

        /**
         * Récupérateur d'informations
         * @param $elements la liste extraite de la page 
         * @param $lang la langue à traiter, pour ajouter les clés manquantes avec la valeur nulle
         * @param $isTasting booléen servant à vérifier si on traite les informations de tasting
         * @param $isAttributs booléen servant à vérifier si on traite les informations d'attributs
         * @return $information tableau associatif avec les valeurs, ou les clés avec une valeur nulle pour chaque
         */
        function extractInformation($elements, $lang, $isTasting = false, $isAttributs = false) {
            $information = [];
            $ajoutCle = [];

            if($elements->count() > 0){

                $elements->each(function (Crawler $element) use (&$information) {

                    $nom = $element->filter('span')->text();
                    $valeur = $element->filter('strong')->text();

                    $information[$nom] = $valeur;
                });
            }

            if($isAttributs){
                if($lang == "fr"){
                    $ajoutCle = [
                        "Région",
                        "Appellation d'origine",
                        "Désignation réglementée",
                        "Classification",
                        "Cépage",
                        "Taux de sucre",
                        "Particularité",
                        "Agent promotionnel",
                        "Code CUP",
                        "Produit du Québec"
                    ];
                }
                elseif($lang == "en"){
                    $ajoutCle = [
                        "Region",
                        "Designation of origin",
                        "Regulated Designation",
                        "Classification",
                        "Grape variety",
                        "Sugar content",
                        "Special feature",
                        "Promoting agent",
                        "UPC code",
                        "Product of Québec",
                    ];
                }
                foreach($ajoutCle as $cle){
                    if(!array_key_exists($cle, $information)){
                        $information[$cle] = null;
                    }
                }
            }

            if($isTasting){
                if($lang == "fr"){
                    $ajoutCle = [
                        "Arômes",
                        "Acidité",
                        "Sucrosité",
                        "Corps",
                        "Bouche",
                        "Bois",
                        "Température de service",
                        "Millésime dégusté",
                        "Potentiel de garde"
                    ];
                }
                elseif($lang == "en"){
                    $ajoutCle = [
                        "Aromas",
                        "Acidity",
                        "Sweetness",
                        "Body",
                        "Mouthfeel",
                        "Wood",
                        "Serving temperature",
                        "Vintage tasted",
                        "Aging potential"
                    ];
                }
                foreach($ajoutCle as $cle){
                    if(!array_key_exists($cle, $information)){
                        $information[$cle] = null;
                    }
                }
            }

            return $information;
        }

        /**
         * Récupérateur d'images et de pastilles
         * @param $selecteur la classe à cibler par le crawler
         * @param $folder le dossier cible pour storer l'image téléchargée
         * @param $includeTitle valeur booléenne pour confirmer si on doit extraire l'attribut title ou pas
         * @return $image tableau associatif contenant le nom de l'image, le texte alternatif et le titre, selon $includeTitle
         */
        function extractImageInformation($selecteur, $folder, $includeTitle = false) {
            $image = null;

            $imageRaw = $this->crawler->filter($selecteur)->first();

            if ($imageRaw->count() > 0) {
                $imageUrl = $imageRaw->attr('src');
                $imageAlt = $imageRaw->attr('alt');
                $imageTitle = $includeTitle ? $imageRaw->attr('title') : null;

                $imageDownload = strtok($imageUrl, '?');

                $responseImage = $this->client->request('GET', $imageDownload);

                if ($responseImage->getStatusCode() === 200) {
                    $imageContent = $responseImage->getBody()->getContents();
                    $imageUrlClean = parse_url($imageUrl, PHP_URL_PATH);
                    $imageFilename = basename($imageUrlClean);

                    $folderName = $folder;

                    if (!Storage::disk('local')->exists($folderName)) {
                        Storage::disk('local')->makeDirectory($folderName);
                    }

                    if (!Storage::disk('local')->exists($folderName . '/' . $imageFilename)) {
                        Storage::disk('local')->put($folderName . '/' . $imageFilename, $imageContent);
                    }

                    $image["url"] = $imageFilename;
                    $image["alt"] = $imageAlt;
                    if ($includeTitle) {
                        $image["title"] = $imageTitle;
                    }
                }
            }

            return $image;
        }


        /**
         * récupérateur du total de pages du catalogue des bouteilles de vin de la SAQ
         * @return float le résultat arrondi au plus haut de la division du nombre de bouteilles par le nombre d'items par page
         */
        public function getNombreTotalPages() {
            // section pour calculer le nombre de page qu'il y a à scraper pour les codes
            $reponse = $this->client->get('https://www.saq.com/fr/produits/vin?p=1&product_list_limit=96&product_list_order=name_asc');

            $htmlNbPages = $reponse->getBody()->getContents();
            $this->crawler->addHtmlContent($htmlNbPages);

            $totalItems = 0;
            $itemsParPage = 96;

            $calculNbPages = $this->crawler->filter('.toolbar-amount')->text();

            if(preg_match('/Résultats\s+(\d+)\s*-\s*(\d+)\s*sur\s*(\d+)/', $calculNbPages, $resultats)){

                $totalItems = (int) $resultats[3];
            }

            // on obtient le nombre de pages ici
            return ceil($totalItems / $itemsParPage);
        }

        /**
         * fonction de mise à jour de la base de données. Effectue la vérification sur le prix et procède à une mise à jour si le prix a changé
         */
        public function miseAJour(){
            $bouteillesExistantes = Bouteille::where('est_scrapee', true)->get();

            foreach ($bouteillesExistantes as $bouteille) {
                $codeSAQ = $bouteille->code_SAQ;
            
                // scraping du prix uniquement pour chaque bouteille
                $url = 'https://www.saq.com/fr/' . $codeSAQ;
                $reponse = $this->client->request('GET', $url);
                $html = $reponse->getBody()->getContents();
                $this->crawler->clear();
                $this->crawler->addHtmlContent($html);
                $prix = $this->crawler->filter('.price')->first()->text();
            
                if($bouteille->prix !== $prix) {
                    // mise à jour du prix de la bouteille
                    $bouteille->prix = $prix;
                    $bouteille->save();
                }
            }

            return view("à compléter");
        }
}
