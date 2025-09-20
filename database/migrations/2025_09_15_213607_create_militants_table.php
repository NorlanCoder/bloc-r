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
        if (!Schema::hasTable('militants')) {
            Schema::create('militants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('photo')->nullable();
            $table->string('sexe')->nullable();
            $table->string('profession')->nullable();
            $table->string('adresse')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('date_inscription')->useCurrent();
            $table->integer('circonscription_id')->nullable()->index();
            $table->integer('departement_id')->nullable()->index();
            $table->integer('commune_id')->nullable()->index();
            $table->foreign('circonscription_id')
                    ->references('code_circ') // le champ clé dans la table cible
                    ->on('circonscriptions') // la table cible
                    ->onDelete('cascade');
            $table->foreign('departement_id')
                    ->references('code_dep') // le champ clé dans la table cible
                    ->on('departements') // la table cible
                    ->onDelete('cascade');
            $table->foreign('commune_id')
                    ->references('code_com') // le champ clé dans la table cible
                    ->on('communes') // la table cible
                    ->onDelete('cascade');
            $table->string('reference_carte')->unique();
            $table->string('status_paiement')->default('unpaid');
            $table->enum('removed', ['yes', 'no'])->default('no');
            $table->string('motif_refus')->nullable();
            $table->enum('status_impression', ['printed', 'not_printed'])->default('not_printed');
            $table->enum('status_verification', ['en_cours', 'correct', 'refuse', 'corrige'])->default('en_cours');
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('militants');
    }
};
