<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description'])]

// "extends Model" : la classe hérite de create(), find(), where()...
// Laravel relie automatiquement la classe Category à la table categories
class Category extends Model
{
    // Une catégorie a PLUSIEURS livres
    public function books(): HasMany
    {
        // hasMany = "a plusieurs"
        // Laravel cherche les livres dont category_id = id de cette catégorie
        return $this->hasMany(Book::class);
    }
}