<?php

namespace App\Http\Controllers;

use App\Models\ListeAchat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\BouteilleAddedToCatalog;
use Illuminate\Support\Facades\Log;
use App\Models\Bouteille;

class ListeAchatController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ListeAchat::class, 'liste_achat');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $bouteilles = ListeAchat::where('user_id', $userId)
            ->with('bouteille') // Eager load the related 'bouteille' model
            ->get();
        $celliers = Auth::user()->celliers;
        return view('listeAchat.index', compact('bouteilles', 'celliers'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'bouteille_id_liste' => 'required|exists:bouteilles,id',
        ]);

        $userId = Auth::id();
        $bouteilleId = $request->bouteille_id_liste;
        $bouteilleDansListe = ListeAchat::where('user_id', $userId)->where('bouteille_id', $bouteilleId)->exists();

        if ($bouteilleDansListe) {
            $message = 'Cette bouteille est déjà dans votre liste d\'achat.';
        } else {
            $listeAchat = ListeAchat::create([
                'user_id' => Auth::id(),
                'bouteille_id' => $bouteilleId,
            ]);

            if ($listeAchat) {
                $bouteille = Bouteille::where('id', $bouteilleId)->first();
                $message = "Vous avez ajouté la bouteille $bouteille->nom à votre liste d'achat.";
            }
        }

        return redirect()->route('liste_achat.index')->with('success', $message);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListeAchat $listeAchat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListeAchat $listeAchat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListeAchat $listeAchat)
    {
        $listeAchat->delete();
        return redirect()->route('liste_achat.index')->with('success', 'La bouteille a été retirée de votre liste d\'achat.');
    }
}
