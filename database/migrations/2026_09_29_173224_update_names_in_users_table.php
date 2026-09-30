<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Modifier la structure de la table users
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'full_name');
            $table->string('first_name', 50)->default('')->after('id');
            $table->string('last_name', 50)->default('')->after('first_name');
        });

        // 2. Remplir first_name et last_name pour les utilisateurs existants
        foreach (DB::table('users')->get() as $user) {
            $parts = explode(' ', $user->full_name, 2);

            DB::table('users')->where('id', $user->id)->update([
                'first_name' => $parts[0],
                'last_name' => $parts[1] ?? '',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
            $table->renameColumn('full_name', 'name');
        });
    }
};