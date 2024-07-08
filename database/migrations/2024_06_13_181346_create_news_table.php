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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 400);
            $table->string('slug');
            $table->text('short_description');
            $table->text('description');
            $table->timestamp('publish_date')->nullable();
            $table->foreignId('author_id')->nullable();
            $table->string('status');
            $table->integer('views')->nullable();
            $table->integer('sort')->nullable();
            $table->string('place')->nullable();
            $table->string('layout')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
