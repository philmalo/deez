<?php

namespace App\Http\Controllers;

use App\Models\CommentaireBouteille;
use Illuminate\Http\Request;

class CommentaireBouteilleController extends Controller
{
    // Gestion de l'autorisation avec la politique
    public function __construct()
    {
        $this->authorizeResource(CommentaireBouteille::class, 'commentaire_bouteille');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bouteille_id' => 'required|exists:bouteilles,id',
            'note' => 'nullable|integer|min:0|max:5',
            'commentaire' => 'nullable|string|max:255',
        ]);

        if($request->filled('note') || $request->filled('commentaire')) {
            $commentaireBouteille = CommentaireBouteille::create([
                'bouteille_id' => $request->bouteille_id,
                'user_id' => auth()->id(),
                'note' => null,
                'commentaire' => "",
            ]);
    
            if ($request->filled('note')) {
                $commentaireBouteille->note = $request->note;
            }
    
            if ($request->filled('commentaire')) {
                $commentaireBouteille->commentaire = $request->commentaire;
            }
        }

        $commentaireBouteille->save();

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommentaireBouteille $commentaireBouteille)
    {
        // Si on a une note
        if($request->note) {
            $request->validate(['note' => '|integer']);
            // On verifie si la note a changée
            if($request->note != $commentaireBouteille->note){
                $commentaireBouteille->update(['note' => $request->note]);
            }
        }

        // Si on a un commentaire
        if($request->commentaire) {
            $request->validate(['commentaire' => 'string']);
            // On verifie si le commentaire a changé
            if($request->commentaire != $commentaireBouteille->commentaire){
                $commentaireBouteille->update(['commentaire' => $request->commentaire]);
            }
        }
        
        return redirect()->back();
    }
}
