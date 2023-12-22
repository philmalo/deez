<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CellierQuantiteBouteille;

class Cellier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        //* Supprime les bouteilles du cellier lors de la suppression du cellier
        static::deleting(function (Cellier $cellier) {
            $cellier->cellierQuantiteBouteille()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cellierQuantiteBouteille()
    {
        return $this->hasMany(CellierQuantiteBouteille::class, 'cellier_id');
    }

    //* retourne la quantité de bouteilles dans le cellier
    public function getQuantiteBouteillesAttribute()
    {
        return $this->cellierQuantiteBouteille()->sum('quantite');
    }

    //*Retourne les couleurs
    public function quantiteBouteillesRouges()
    {
        return $this->cellierQuantiteBouteille()
        ->join('bouteilles', 'cellier_quantite_bouteilles.bouteille_id', '=', 'bouteilles.id')
        ->where('bouteilles.couleur_fr', 'Rouge')
        ->sum('quantite');
    }

    public function quantiteBouteillesRoses()
    {
        return $this->cellierQuantiteBouteille()
        ->join('bouteilles', 'cellier_quantite_bouteilles.bouteille_id', '=', 'bouteilles.id')
        ->where('bouteilles.couleur_fr', 'Rosé')
        ->sum('quantite');
    }

    public function quantiteBouteillesBlanches()
    {
        return $this->cellierQuantiteBouteille()
        ->join('bouteilles', 'cellier_quantite_bouteilles.bouteille_id', '=', 'bouteilles.id')
        ->where('bouteilles.couleur_fr', 'Blanc')
        ->sum('quantite');
    }

    public function quantiteBouteillesOranges()
    {
        return $this->cellierQuantiteBouteille()
            ->join('bouteilles', 'cellier_quantite_bouteilles.bouteille_id', '=', 'bouteilles.id')
            ->where('bouteilles.particularite_fr', 'LIKE', '%orange%')
            ->sum('quantite');
    }

    public function bouteilles()
    {
        return $this->belongsToMany(Bouteille::class, 'cellier_quantite_bouteilles')
                    ->withPivot('quantite');
    }

}
