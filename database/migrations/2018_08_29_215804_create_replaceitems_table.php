<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplaceitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replaceitems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('product_category');
            $table->string('product_name');
            $table->decimal('product_price');
            $table->integer('product_quantity');
            $table->string('product_image');
            $table->string('product_type');
            $table->integer('sal_new');
            $table->integer('replace_sell');
            $table->integer('status');
            $table->text('product_description');

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
        Schema::dropIfExists('replaceitems');
    }
}
