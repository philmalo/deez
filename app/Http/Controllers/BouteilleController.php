<?php

namespace App\Http\Controllers;

use App\Models\Bouteille;
use Illuminate\Http\Request;
use App\Models\Cellier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CommentaireBouteille;
use Illuminate\Support\Facades\Log;

class BouteilleController extends Controller
{
    // Gestion de l'autorisation avec la politique
    public function __construct()
    {
        $this->authorizeResource(Bouteille::class, 'bouteille');
    }

    /**
     * Ici on ne rentre pas dans le if quand on arrive sur la page, 
     * c'est seulement axios qui va faire une requête quand on tape dans la barre de recherche
     * En tant qu'usager on entre toujours dans le else
     */
    public function index(Request $request)
    {
        $cleanCountryNamesFr = [
            'afrique_du_sud' => 'Afrique du Sud',
            'allemagne' => 'Allemagne',
            'argentine' => 'Argentine',
            'armenie' => 'Arménie (République d\')',
            'australie' => 'Australie',
            'autriche' => 'Autriche',
            'bresil' => 'Brésil',
            'bulgarie' => 'Bulgarie',
            'canada' => 'Canada',
            'chili' => 'Chili',
            'chine' => 'Chine',
            'croatie' => 'Croatie',
            'espagne' => 'Espagne',
            'etats_unis' => 'États-Unis',
            'france' => 'France',
            'georgie' => 'Géorgie',
            'germany' => 'germany',
            'grece' => 'Grèce',
            'hongrie' => 'Hongrie',
            'israel' => 'Israël',
            'italie' => 'Italie',
            'liban' => 'Liban',
            'luxembourg' => 'Luxembourg',
            'maroc' => 'Maroc',
            'mexique' => 'Mexique',
            'moldavie' => 'Moldavie',
            'nouvelle_zelande' => 'Nouvelle-Zélande',
            'portugal' => 'Portugal',
            'republique_tcheque' => 'République Tchèque',
            'roumanie' => 'Roumanie',
            'slovaquie' => 'Slovaquie',
            'slovenie' => 'Slovénie',
            'suisse' => 'Suisse',
            'uruguay' => 'Uruguay'
        ];

        $cleanCountryNamesEn = [
            'argentina' => 'Argentina',
            'armenia' => 'Armenia',
            'australia' => 'Australia',
            'austria' => 'Austria',
            'brazil' => 'Brazil',
            'bulgaria' => 'Bulgaria',
            'canada' => 'Canada',
            'chile' => 'Chile',
            'china' => 'China',
            'croatia' => 'Croatia',
            'czech_republic' => 'Czech Republic',
            'france' => 'France',
            'georgia' => 'Georgia',
            'germany' => 'Germany',
            'greece' => 'Greece',
            'hungary' => 'Hungary',
            'israel' => 'Israel',
            'italy' => 'Italy',
            'lebanon' => 'Lebanon',
            'luxembourg' => 'Luxembourg',
            'mexico' => 'Mexico',
            'moldova' => 'Moldova, Republic of',
            'morocco' => 'Morocco',
            'new_zealand' => 'New Zealand',
            'portugal' => 'Portugal',
            'romania' => 'Romania',
            'slovakia' => 'Slovakia',
            'slovenia' => 'Slovenia',
            'south_africa' => 'South Africa',
            'spain' => 'Spain',
            'switzerland' => 'Switzerland',
            'united_states' => 'United States',
            'uruguay' => 'Uruguay',
        ];

        $localisation = app()->getLocale(); // Obtenir la localisation actuelle (fr ou en)
        $pays = ($localisation === "fr") ? $cleanCountryNamesFr : $cleanCountryNamesEn;

        $searchTerm = $request->input('query');
        $rouge = $request->rouge;
        $blanc = $request->blanc;
        $rose = $request->rose;
        $orange = $request->orange;
        
        $moinsDeVingt = $request->input('1-20');
        $vingtTrente = $request->input('20-30');
        $trenteQuarante = $request->input('30-40');
        $quaranteCinquante = $request->input('40-50');
        $cinquanteSoixante = $request->input('50-60');
        $plusQueSoixante = $request->input('60');
        $tri = $request->tri;
        
        // Eloquent query builder
        $query = Bouteille::query();
        
        if ($tri) {
            // Split the $tri parameter into field and direction
            list($sortField, $sortDirection) = explode('-', $tri);

            // Validate sorting direction
            $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'asc';
            // Apply sorting to the query
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('nom', 'asc');
        }
        // La recherche en soit
        if ($searchTerm) {
            $query->where('nom', 'like', "%$searchTerm%");
        }

        $selectedCountries = [];

        foreach ($pays as $paysClean => $paysOfficiel) {
            if ($request->has($paysClean)) {
                $selectedCountries[] = $paysOfficiel;
            }
        }

        if (!empty($selectedCountries)) {
            $query->where(function ($subquery) use ($selectedCountries) {
                $subquery->whereIn('pays_fr', $selectedCountries)
                        ->orWhereIn('pays_en', $selectedCountries);
            });
        }

        $cepageEntries = Bouteille::select('cepage')
            ->distinct()
            ->get()
            ->pluck('cepage')
            ->flatMap(function ($cepage) {
                $cepageArray = explode(', ', $cepage);
                return array_map(function ($entry) {
                                    // Convertir les codes Unicode en caractères
                    $cleanedEntry = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($matches) {
                        return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16BE');
                    }, $entry);

                    // Supprimer les caractères spéciaux U+A0 (espaces insécables)
                    $cleanedEntry = str_replace("\u{A0}", '', $cleanedEntry);

                    // Supprimer les caractères non alphabétiques, espaces, tirets et parenthèses
                    $cleanedEntry = preg_replace('/[^\p{L}\s\-()]+/u', '', $cleanedEntry);

                    // Supprimer les espaces insécables en utilisant \s
                    $cleanedEntry = preg_replace('/\s+/u', ' ', $cleanedEntry);

                    return trim($cleanedEntry);
                }, $cepageArray);
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $selectedCepages = [];

        foreach ($cepageEntries as $cepage) {
            $cepageField = str_replace(' ', '_', $cepage);
            if ($request->has($cepageField)) {
                $selectedCepages[] = $cepage;
            }
        }

        if (!empty($selectedCepages)) {
            $query->where(function ($subquery) use ($selectedCepages) {
                foreach ($selectedCepages as $cepage) {
                    $subquery->orWhere('cepage', 'LIKE', '%' . $cepage . '%');
                }
            });
        }

        // Par couleur, avec recherche dans un autre champ pour les vins orange
        if ($rouge || $blanc || $rose || $orange) {

            $query->where(function ($subquery) use ($rouge, $blanc, $rose, $orange) {

                $colors = array_filter([$rouge, $blanc, $rose]);
                $subquery->whereIn('couleur_fr', $colors)
                        ->orWhereIn('couleur_en', $colors);

                if ($orange) {

                    $subquery->orWhere('particularite_fr', 'LIKE', "%$orange%");
                }
            });
        }

        // Filtrer par gamme de prix
        $selectedPriceRanges = [];

        if ($moinsDeVingt) {
            $selectedPriceRanges[] = '1-20';
        }
        if ($vingtTrente) {
            $selectedPriceRanges[] = '20-30';
        }
        if ($trenteQuarante) {
            $selectedPriceRanges[] = '30-40';
        }
        if ($quaranteCinquante) {
            $selectedPriceRanges[] = '40-50';
        }
        if ($cinquanteSoixante) {
            $selectedPriceRanges[] = '50-60';
        }
        if ($plusQueSoixante) {
            $selectedPriceRanges[] = '60';
        }

        $priceRanges = [
            '1-20' => [1, 19],
            '20-30' => [20, 30],
            '30-40' => [30, 40],
            '40-50' => [40, 50],
            '50-60' => [50, 60],
            '60' => [60, PHP_INT_MAX],
        ];

        if (!empty($selectedPriceRanges)) {
            $query->where(function ($subquery) use ($priceRanges, $selectedPriceRanges) {
                foreach ($selectedPriceRanges as $selectedRange) {
                    if (isset($priceRanges[$selectedRange])) {
                        list($minPrice, $maxPrice) = $priceRanges[$selectedRange];
                        $subquery->orWhereBetween('prix', [$minPrice, $maxPrice]);
                    }
                }
            });
        }
        

        $tastesCorrespondence = [
            'Aromatique et charnu' => 'Aromatic and robust',
            'Aromatique et rond' => 'Aromatic and mellow',
            'Aromatique et souple' => 'Aromatic and supple',
            'Délicat et léger' => 'Delicate and light',
            'Fruité et doux' => 'Fruity and sweet',
            'Fruité et extra-doux' => 'Fruity and extra sweet',
            'Fruité et généreux' => 'Fruity and medium-bodied',
            'Fruité et léger' => 'Fruity and light',
            'Fruité et vif' => 'Fruity and vibrant',
        ];
        
        $selectedTastes = [];

        foreach ($tastesCorrespondence as $frenchTaste => $englishTaste) {
            $frenchParameter = str_replace(' ', '_', $frenchTaste);
            $englishParameter = str_replace(' ', '_', $englishTaste);
            
            if ($request->has($frenchParameter)) {
                $selectedTastes[] = $frenchTaste;
            }
            
            if ($request->has($englishParameter)) {
                $selectedTastes[] = $frenchTaste;
            }
        }

        $query->where(function ($subquery) use ($selectedTastes) {
            foreach ($selectedTastes as $frenchTaste) {
                $subquery->orWhere('image_pastille_alt', 'LIKE', '%' . $frenchTaste . '%');
            }
        });

        if ($tri) {
            // Split the $tri parameter into field and direction
            list($sortField, $sortDirection) = explode('-', $tri);

            // Validate sorting direction
            $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'asc';
            // Apply sorting to the query
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('nom', 'asc');
        }
    
        // Get paginated results
        $bouteilles = $query->paginate(30);
        
        $message = __('messages.add');
        foreach ($bouteilles as $bouteille) {
            $bouteille->message = $message;
            $bouteille->nombreBouteilles = $bouteilles->total();
        }


    
        if ($request->ajax()) {
            return response()->json($bouteilles);
        }
        else {

            $celliers = Cellier::where('user_id', auth()->id())->get();

            
            
            $pastilles = Bouteille::select('image_pastille_alt')->distinct()->get()->sortBy('image_pastille_alt');
            $cepages = $cepageEntries;
            return view('bouteilles.index', compact('celliers', 'pays', 'cepages', 'pastilles'));
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bouteilles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // On valide les champs
        $request->validate([
            'nom' => 'required|string|max:255',
            'pays' => 'string|max:255',
            'region' => 'string|max:255',
            'description' => 'string|max:255',
            'image_bouteille' => 'image|max:2048',
            'user_id' => 'exists:users,id',
        ]);

        // On crée la bouteille
        $bouteille = Bouteille::create([
            'nom' => $request->nom,
            'pays_fr' => $request->pays,
            'pays_en' => $request->pays,
            'region_fr' => $request->region,
            'region_en' => $request->region,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'est_personnalisee' => true,
        ]);

        // On ajoute l'image si il y en a une
        if ($request->hasFile('image_bouteille')) {
            $file = $request->file('image_bouteille');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . $bouteille->id . '.' . $extension;
            // On crée le dossier si il n'existe pas
            if (!Storage::disk('local')->exists('imagesPersonnalisees')) {
                Storage::disk('local')->makeDirectory('imagesPersonnalisees');
            }
            // On enregistre l'image
            Storage::disk('local')->putFileAs('imagesPersonnalisees', $file, $fileName);
            $bouteille->image_bouteille = $fileName;
            // On sauvegarde la bouteille
            $bouteille->save();
        } else {
            // On met une image par défaut si il n'y en a pas
            $bouteille->image_bouteille = '06.png';
            // On sauvegarde la bouteille
            $bouteille->save();
        }

        return redirect()
                ->route('bouteilles.show', $bouteille);  
    }

    /**
     * Display the specified resource.
     */
    public function show(Bouteille $bouteille)
    {
        // On récupère le commentaire de l'usager connecté
        $commentaireBouteille = CommentaireBouteille::
            where('bouteille_id', $bouteille->id)
            ->where('user_id', auth()->id())
            ->get()->first();
        // On récupère les celliers de l'usager connecté
        $celliers = Cellier::where('user_id', auth()->id())->get();

        return view('bouteilles.show', compact('bouteille', 'commentaireBouteille', 'celliers'));
    }
}
