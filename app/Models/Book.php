<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;   

#[Fillable(['title', 'isbn', 'published_year', 'copies', 'category_id'])]
class Book extends Model
{
    // Un livre appartient à UNE catégorie
    public function category(): BelongsTo
    {
        // belongsTo = "appartient à"
        // Laravel lit la colonne category_id de ce livre
        // et va chercher la catégorie qui a cet id
        return $this->belongsTo(Category::class);
    }
    // Un livre peut avoir PLUSIEURS auteurs (Many-to-Many)
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }
    // Les membres qui ont emprunté ce livre (Many-to-Many, pivot avec infos)
    public function borrowers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'loans')   // 'loans' = nom du pivot
            ->withPivot('borrowed_at', 'due_date', 'returned_at', 'status') // colonnes en plus
            ->withTimestamps();                               // remplit created_at / updated_at
    }
}