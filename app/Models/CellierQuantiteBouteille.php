<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bouteille;
use App\Models\Cellier;

class CellierQuantiteBouteille extends Model
{
    use HasFactory;

    protected $fillable = [
        'cellier_id',
        'bouteille_id',
        'quantite',
    ];

    public function bouteille()
    {
        return $this->belongsTo(Bouteille::class);
    }

    public function cellier()
    {
        return $this->belongsTo(Cellier::class);
    }
}
