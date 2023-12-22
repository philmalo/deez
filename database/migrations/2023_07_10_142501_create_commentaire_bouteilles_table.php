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
        Schema::create('commentaire_bouteilles', function (Blueprint $table) {
            $table->id();
            $table->text('commentaire')->nullable();
            $table->integer('note')->max(5)->unsigned()->nullable()->default(0)->comment('Note sur 5');
            $table->bigInteger('bouteille_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaire_bouteilles');
    }
};
