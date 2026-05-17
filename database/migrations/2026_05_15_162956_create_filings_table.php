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
        Schema::create('filings', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('region');
            $table->string('province');
            $table->string('city')->nullable();
            $table->integer('min_year')->nullable();
            $table->integer('max_year')->nullable();
            $table->boolean('is_certain_date')->default(false);
            $table->boolean('is_sacred_dedication')->default(false);
            $table->text('notes')->nullable();
            $table->enum('religion', ['uncertain', 'pagan', 'christian']);
            $table->softDeletes();
            $table->foreignId('proposed_by')->constrained('users');
            $table->foreignId('approved_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filings');
    }
};
