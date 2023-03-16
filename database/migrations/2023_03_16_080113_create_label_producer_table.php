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
        //Attention la table doit etre écrit dans ce sens label_producer et sans les s
        Schema::create('label_producer', function (Blueprint $table) {
            // Attention pas de s à producer_id
            $table->bigInteger('producer_id')->unsigned()->nullable();
            $table->foreign('producer_id')
                    ->references('id')
                    ->on('producers');

            // Attention pas de s à label_id        
            $table->bigInteger('label_id')->unsigned()->nullable();
            $table->foreign('label_id')
                    ->references('id')
                    ->on('labels');
            $table->id();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_producer');
    }
};
