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
    // Nom "loans" (emprunts) : ne suit pas la convention book_user,
    // donc il faudra l'écrire dans les relations
    Schema::create('loans', function (Blueprint $table) {

        // id propre à chaque emprunt
        // Utile car un membre peut emprunter LE MÊME livre plusieurs fois
        $table->id();

        // Le membre qui emprunte (lien vers la table users)
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // Le livre emprunté (lien vers la table books)
        $table->foreignId('book_id')->constrained()->cascadeOnDelete();

        // ⬇️ Les informations EN PLUS du pivot
        $table->date('borrowed_at');               // date d'emprunt
        $table->date('due_date');                  // QUAND il doit être rendu (date limite)
        $table->date('returned_at')->nullable();   // date de retour (vide tant que pas rendu)

        // Statut : seulement les 3 valeurs de l'enum
        $table->enum('status', ['en_cours', 'rendu', 'en_retard'])->default('en_cours');

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
