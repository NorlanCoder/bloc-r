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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('type_operation')->default('impression');
            $table->timestamp('date_operation')->useCurrent();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('impression_id')->constrained('impressions')->onDelete('cascade')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
