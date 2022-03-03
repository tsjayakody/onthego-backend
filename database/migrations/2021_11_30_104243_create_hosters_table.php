<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosters', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description');
            $table->string('email', 200)->unique();
            $table->string('contact_number', 10)->unique();
            $table->text('twitter')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('facebook')->nullable();
            $table->text('instagram')->nullable();
            $table->text('youtube')->nullable();
            $table->text('anchor')->nullable();
            $table->text('past_works')->nullable();
            $table->text('slug');
            $table->string('designation');
            $table->boolean('is_approved')->default(false);
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
        Schema::dropIfExists('hosters');
    }
}
