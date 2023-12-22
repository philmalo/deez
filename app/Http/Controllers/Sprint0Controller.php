<?php

namespace App\Http\Controllers;

use App\Models\Bouteille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Sprint0Controller extends Controller
{

    public function demoListe() {

        $bouteilles = Bouteille::all();
        $codesTraites = 0;
        $erreur = [];

        return view('sprint0.liste', ["bouteilles" => $bouteilles, "erreurs" => $erreur, "codesTraites" => $codesTraites, "lang" => "fr"]);
    }
}
