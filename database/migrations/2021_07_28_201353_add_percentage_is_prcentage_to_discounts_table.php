<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentageIsPrcentageToDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->integer('percentage')->nullable()->default(0);
            $table->boolean('is_percentage')->nullable()->comment('0=no, 1=yes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->integer('percentage')->nullable()->default(0);
            $table->boolean('is_percentage')->nullable()->comment('0=no, 1=yes')->default(0);
        });
    }
}
