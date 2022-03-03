<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('slug');
            $table->text('description');
            $table->text('media_url');
            $table->text('tags')->nullable();
            $table->text('spotify')->nullable();
            $table->text('google_podcast')->nullable();
            $table->text('apple_podcast')->nullable();
            $table->text('featured_hosts')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->foreignId('show_id')->constrained('shows', 'id')->onDelete('cascade');
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
        Schema::dropIfExists('episodes');
    }
}
