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
        Schema::create('editions', function (Blueprint $table) {
            $table->id();
            $table->string('corpus');
            $table->tinyInteger('volume')->nullable();
            $table->mediumInteger('number_inscription')->nullable();
            $table->integer('publication_year')->nullable();
            $table->mediumInteger('corpus_page')->nullable();
            $table->string('last_name_author')->nullable();
            $table->foreignId('filing_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('editions');
    }
};
