<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id');
            $table->char('name', 20);
            $table->text('description');
            // $table->integer('purchased_price');
            $table->string('img_upload_url');
            $table->primary(['id', 'user_id']);
            // $table->integer('Sale_price');
            $table->timestamps();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
