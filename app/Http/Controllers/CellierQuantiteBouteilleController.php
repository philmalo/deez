<?php

namespace App\Http\Controllers;

use App\Models\CellierQuantiteBouteille;
use Illuminate\Http\Request;

class CellierQuantiteBouteilleController extends Controller
{
    // Gestion de l'autorisation avec la politique
    public function __construct()
    {
        $this->authorizeResource(CellierQuantiteBouteille::class, 'cellier_quantite_bouteille');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cellierQuantiteBouteille = CellierQuantiteBouteille::where('cellier_id', $request->cellier_id)
            ->where('bouteille_id', $request->bouteille_id)
            ->first();

        // Check if the bottle quantity already exists in the cellar
        if ($cellierQuantiteBouteille) {
            $request->source_page == 'bouteilles.index' ? 
                $cellierQuantiteBouteille->quantite += $request->quantite
              : $cellierQuantiteBouteille->quantite = $request->quantite;

            $cellierQuantiteBouteille->save();
        } else {
            // On valide les données
            $request->validate([
                'cellier_id' => 'required|integer',
                'bouteille_id' => 'required|integer',
                'quantite' => 'required|integer',
            ]);
            
            // On crée une nouvelle entrée dans la table cellier_quantite_bouteille
            $cellierQuantiteBouteille = CellierQuantiteBouteille::create([
                'cellier_id' => $request->cellier_id,
                'bouteille_id' => $request->bouteille_id,
                'quantite' => $request->quantite,
            ]);
        }
        
        // Definition des variables pour le message de succès
        $quantite = $request->quantite;
        $nomBouteille = $cellierQuantiteBouteille->bouteille->nom;
        $nomCellier = $cellierQuantiteBouteille->cellier->nom;

        // Redirection vers la page du cellier avec un message de succès
        return redirect()
                ->route('celliers.show', $request->cellier_id)
                ->with('success', trans('messages.add_bottle', compact('quantite', 'nomBouteille', 'nomCellier')));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CellierQuantiteBouteille $cellierQuantiteBouteille)
    {
        // On valide les données
        $validation = $request->validate([
            'nouvelleQuantite' => 'required|integer'
        ]);

        $ancienneQuantite = $cellierQuantiteBouteille->quantite;
        $nouvelleQuantite = $validation['nouvelleQuantite'];
        $difference = abs($nouvelleQuantite - $ancienneQuantite);

        $cellierQuantiteBouteille->quantite = $nouvelleQuantite;
        $cellierQuantiteBouteille->save();

        $cellierQuantiteBouteille->load('bouteille');

        $nomBouteille = $cellierQuantiteBouteille->bouteille->nom;
        $nomCellier = $cellierQuantiteBouteille->cellier->nom;

        $messageKey = ($nouvelleQuantite > $ancienneQuantite) ? 'edit_bottle_more' : 'edit_bottle_less';
        $message = trans("messages.$messageKey", compact('difference', 'nomBouteille', 'nomCellier'));

        return redirect()
                ->route('celliers.show', $cellierQuantiteBouteille->cellier_id)
                ->with('success', $message);

    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(CellierQuantiteBouteille $cellierQuantiteBouteille)
    public function destroy(CellierQuantiteBouteille $cellierQuantiteBouteille)
    {
        $cellierQuantiteBouteille->load('bouteille');
        $nomBouteille = $cellierQuantiteBouteille->bouteille->nom;
        
        $cellierQuantiteBouteille->delete();
        $nomCellier = $cellierQuantiteBouteille->cellier->nom;

        return redirect()
                ->route('celliers.show', $cellierQuantiteBouteille->cellier_id)
                ->with('success', trans('messages.delete_bottle', compact('nomBouteille', 'nomCellier')));
    }
}
