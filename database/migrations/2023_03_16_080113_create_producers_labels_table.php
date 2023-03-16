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
        Schema::create('producers_labels', function (Blueprint $table) {
            $table->bigInteger('producers_id')->unsigned()->nullable();
            $table->foreign('producers_id')
                    ->references('id')
                    ->on('producers');
            $table->bigInteger('labels_id')->unsigned()->nullable();
            $table->foreign('labels_id')
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
        Schema::dropIfExists('producers_labels');
    }
};
