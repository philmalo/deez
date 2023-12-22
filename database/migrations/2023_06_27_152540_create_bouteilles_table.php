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
        Schema::create('bouteilles', function (Blueprint $table) {
            $table->id();
            // section tasting
            $table->string('aromes_fr')->nullable();
            $table->string('aromes_en')->nullable();
            $table->string('acidite_fr')->nullable();
            $table->string('acidite_en')->nullable();
            $table->string('sucrosite_fr')->nullable();
            $table->string('sucrosite_en')->nullable();
            $table->string('corps_fr')->nullable();
            $table->string('corps_en')->nullable();
            $table->string('bouche_fr')->nullable();
            $table->string('bouche_en')->nullable();
            $table->string('bois_fr')->nullable();
            $table->string('bois_en')->nullable();
            $table->string('temperature_fr')->nullable();
            $table->string('temperature_en')->nullable();
            $table->string('millesime')->nullable();
            $table->string('potentiel_de_garde_fr')->nullable();
            $table->string('potentiel_de_garde_en')->nullable();
            // section attributs
            $table->string('pays_fr')->nullable();
            $table->string('pays_en')->nullable();
            $table->string('region_fr')->nullable();
            $table->string('region_en')->nullable();
            $table->string('designation_reglementee_fr')->nullable();
            $table->string('designation_reglementee_en')->nullable();
            $table->string('classification_fr')->nullable();
            $table->string('classification_en')->nullable();
            $table->string('cepage')->nullable();
            $table->string('degree_alcool')->nullable();
            $table->string('taux_de_sucre')->nullable();
            $table->string('couleur_fr')->nullable();
            $table->string('couleur_en')->nullable(); 
            $table->string('format')->nullable();
            $table->string('producteur')->nullable();
            $table->string('agent_promotionnel')->nullable();
            $table->string('code_SAQ')->nullable();
            $table->string('code_CUP')->nullable();

            $table->string('produit_quebec_fr')->nullable();
            $table->string('produit_quebec_en')->nullable();

            $table->string('particularite_fr')->nullable();
            $table->string('particularite_en')->nullable();
            $table->string('appellation_origine')->nullable();

            // données séparées
            $table->string('nom')->nullable();
            $table->string('image_bouteille')->nullable();
            $table->string('image_bouteille_alt')->nullable();
            $table->decimal('prix', 10, 2)->nullable();
            $table->string('image_pastille')->nullable();
            $table->string('image_pastille_alt')->nullable();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();

            // booléen pour scraper ou non les détails de la bouteille
            $table->boolean('est_scrapee')->default(false);
            $table->boolean('est_personnalisee')->default(false);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->boolean('existe_plus')->default(false);
            $table->boolean('est_utilisee')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bouteilles');
    }
};
