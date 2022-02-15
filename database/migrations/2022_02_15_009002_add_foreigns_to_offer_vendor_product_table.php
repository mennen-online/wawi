<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignsToOfferVendorProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_vendor_product', function (Blueprint $table) {
            $table
                ->foreign('vendor_product_id')
                ->references('id')
                ->on('vendor_products')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_vendor_product', function (Blueprint $table) {
            $table->dropForeign(['vendor_product_id']);
            $table->dropForeign(['offer_id']);
        });
    }
}
