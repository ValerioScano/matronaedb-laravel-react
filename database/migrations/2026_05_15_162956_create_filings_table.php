<?php

use App\Enums\Macroarea;
use App\Enums\Province;
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
            $table->enum('macroarea', Macroarea::values())->index();
            $table->enum('province', Province::values())->index();
            $table->string('city')->nullable()->index();
            $table->smallInteger('min_year')->nullable()->index();
            $table->smallInteger('max_year')->nullable()->index();
            $table->boolean('is_certain_date')->default(false);
            $table->boolean('is_sacred_dedication')->default(false);
            $table->text('notes')->nullable();
            $table->enum('religion', ['uncertain', 'pagan', 'christian']);
            $table->softDeletes();
            $table->foreignId('proposed_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->constrained('users')->restrictOnDelete();
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
