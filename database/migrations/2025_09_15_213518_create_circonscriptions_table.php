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
        Schema::create('circonscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('code_circonscription')->unique();
            $table->string('circonscription_label');
            $table->foreignId('code_departement')->constrained('departements')->onDelete('cascade');
            $table->string('lib_iso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circonscriptions');
    }
};
