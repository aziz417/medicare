<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('gateway')->default('manual');
            $table->string('method')->nullable();
            $table->double('amount');
            $table->string('currency')->default('BDT');
            $table->integer('discount');
            $table->string('discount_code')->nullable();
            $table->double('final_amount');
            $table->double('received_amount')->nullable();
            $table->integer('tax')->default(0);
            $table->text('response')->nullable();
            $table->string('payment_id', 100)->nullable();
            $table->string('payment_url', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->text('approved_by')->nullable();
            $table->string('type')->default('transaction');
            $table->string('access_token', 100)->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('transactions');
    }
}
