<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_code', 100)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('type')->default('booking');
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->datetime('scheduled_at')->nullable();
            $table->integer('duration')->default(15);
            $table->integer('appointment_fee');
            $table->integer('discount')->default(0);
            $table->string('coupon_code')->nullable();
            $table->text('patient_problem')->nullable();
            $table->string('comment', 255)->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_completed')->default(0);
            $table->boolean('notified')->default(0);
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
        Schema::dropIfExists('appointments');
    }
}
