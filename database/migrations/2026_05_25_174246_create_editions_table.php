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
            $table->string('corpus')->index();
            $table->tinyInteger('volume')->nullable()->index();
            $table->mediumInteger('number_inscription')->nullable()->index();
            $table->unsignedSmallInteger('publication_year')->nullable();
            $table->mediumInteger('corpus_page')->nullable();
            $table->string('last_name_author')->nullable();
            $table->string('edition_image')->nullable();
            $table->morphs('editionable');
            $table->softDeletes();
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
