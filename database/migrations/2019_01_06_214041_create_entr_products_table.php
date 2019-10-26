<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entr_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('category');
            $table->string('product_name');
            $table->string('type');
            $table->integer('quantity');
            $table->decimal('price');
            $table->string('sal_new');
            $table->string('replace_sale');
            $table->text('description')->nullable();
            $table->string('featured_image');
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('entr_products');
    }
}
