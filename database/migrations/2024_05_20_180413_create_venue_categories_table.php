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
        Schema::create('venue_categories', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('venue_category_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_category_id');
            $table->unsignedBigInteger('language_id');

            $table->string('name');
            $table->text('description');

            $table->unique(['venue_category_id', 'language_id']);

            $table->foreign('venue_category_id')->references('id')->on('venue_categories')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_categories');
        Schema::dropIfExists('venue_category_translations');
    }
};
