<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
{
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        // Code international du livre (13 caractères)
        // unique() : deux livres ne peuvent pas avoir le même ISBN
        $table->string('isbn', 13)->unique();
        $table->unsignedSmallInteger('published_year')->nullable();
        $table->unsignedInteger('copies')->default(1);
        
         //  LA COLONNE DU LIEN
        // foreignId('category_id') : crée une colonne qui contient l'id d'une catégorie
        // constrained() : vérifie que cette catégorie EXISTE dans la table categories
        // restrictOnDelete() : interdit de supprimer une catégorie qui a encore des livres
       
        $table->foreignId('category_id')->constrained()->restrictOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
