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
        Schema::create('militants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('photo')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('date_inscription')->useCurrent();
            $table->foreignId('circonscription_id')->constrained('circonscriptions')->onDelete('cascade');
            $table->foreignId('departement_id')->constrained('departements')->onDelete('cascade');
            $table->foreignId('commune_id')->constrained('communes')->onDelete('cascade');
            $table->string('reference_carte')->unique();
            $table->string('status_paiement')->default('unpaid');
            $table->enum('removed', ['yes', 'no'])->default('no');
            $table->string('motif_refus')->nullable();
            $table->enum('status_impression', ['printed', 'not_printed'])->default('not_printed');
            $table->enum('status_verification', ['en_cours', 'correct', 'refuse', 'corrige'])->default('en_cours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('militants');
    }
};
