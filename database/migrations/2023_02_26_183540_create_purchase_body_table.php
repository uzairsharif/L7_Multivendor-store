<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseBodyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_body', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_head_id');
            $table->string('product_id');
            $table->string('user_id');
            $table->string('product_purchase_qty');
            $table->string('product_purchase_rate');
            $table->string('product_sale_price');
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
        Schema::dropIfExists('purchase_body');
    }
}
