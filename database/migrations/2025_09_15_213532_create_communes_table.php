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
        Schema::create('communes', function (Blueprint $table) {
            $table->integer('code_com')->nullable()->index();
            $table->string('lib_com', 32)->nullable();
            $table->integer('code_circ')->nullable();
            $table->integer('code_dep')->nullable();
            
            $table->foreign('code_circ')->references('code_circ')->on('circonscriptions');
            $table->foreign('code_dep')->references('code_dep')->on('departements');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communes');
    }
};
