<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('praenomen')->nullable();
            $table->string('nomen')->nullable();
            $table->string('cognomen')->nullable();
            $table->unsignedInteger('TM_PER_id')->nullable();
            $table->morphs('peopleable');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
