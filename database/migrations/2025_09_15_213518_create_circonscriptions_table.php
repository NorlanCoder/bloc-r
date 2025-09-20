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
            $table->integer('code_circ')->nullable()->index();
            $table->string('lib_circ', 32)->nullable();
            $table->integer('code_dep')->nullable();
            $table->string('lib_iso', 10);
            
            $table->foreign('code_dep')->references('code_dep')->on('departements');
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
