<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_vendor_product', function (Blueprint $table) {
            $table->id()->after('offer_id');
            $table->integer('quantity')->default(1)->after('offer_id');
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
        Schema::table('offer_vendor_product', function (Blueprint $table) {
            $table->dropColumn('quantity', 'id', 'created_at', 'updated_at');
        });
    }
};
