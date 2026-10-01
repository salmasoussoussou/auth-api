<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;   
#[Fillable(['first_name', 'last_name', 'nationality'])]
class Author extends Model
{
    // Un auteur a écrit PLUSIEURS livres
    // belongsToMany = relation Many-to-Many (passe par le pivot author_book)
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}