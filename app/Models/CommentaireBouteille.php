<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentaireBouteille extends Model
{
    use HasFactory;

    protected $fillable = [
        'bouteille_id',
        'commentaire',
        'note',
        'user_id',
    ];

    public function bouteille()
    {
        return $this->belongsTo(Bouteille::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
