<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogoTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logo_translation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logo_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('short_description')->nullable()->default(null);
            $table->boolean('external_url')->default(false);
            $table->text('url')->nullable()->default(null);
            $table->timestamps();

            $table->unique(['logo_id', 'locale']);
            $table->foreign('logo_id')->references('id')->on('logos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logo_translation');
    }
}
