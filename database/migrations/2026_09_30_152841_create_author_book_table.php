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
    // Nom : les 2 tables au SINGULIER, par ordre ALPHABÉTIQUE (author avant book)
    // C'est la convention de Laravel : il trouvera la table tout seul
    Schema::create('author_book', function (Blueprint $table) {

        // id de l'auteur ; constrained() vérifie qu'il existe dans "authors"
        // cascadeOnDelete() : si on supprime l'auteur, ses liens sont supprimés
        $table->foreignId('author_id')->constrained()->cascadeOnDelete();

        // id du livre ; même principe avec la table "books"
        $table->foreignId('book_id')->constrained()->cascadeOnDelete();

        // Empêche de lier 2 fois le même auteur au même livre
        $table->primary(['author_id', 'book_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
