<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->string('tool_type')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('description')->nullable();
            $table->string('model')->nullable();
            $table->string('weight')->nullable();
            $table->string('sound_power')->nullable();
            $table->string('quantity')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('status')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('product_models');
    }
}
