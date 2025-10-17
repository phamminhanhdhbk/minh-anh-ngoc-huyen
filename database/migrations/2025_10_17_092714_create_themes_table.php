<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('view_path'); // Path to blade view
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable(); // Preview image
            $table->string('author')->nullable();
            $table->string('version')->default('1.0.0');
            $table->text('settings')->nullable(); // Theme-specific settings as JSON string
            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('themes');
    }
}
