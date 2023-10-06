<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnBodyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_body', function (Blueprint $table) {
            $table->id();
            $table->string('sale_return_head_id');
            $table->string('product_id');
            $table->string('user_id');
            $table->string('product_qty');
            $table->float('product_sale_rate');
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
        Schema::dropIfExists('sale_return_body');
    }
}
