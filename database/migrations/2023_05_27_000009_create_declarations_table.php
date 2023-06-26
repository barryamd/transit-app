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
        Schema::create('declarations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('folder_id');
            $table->string('number', 30)->unique();
            $table->date('date');
            $table->string('destination_office');
            $table->string('verifier', 30);
            $table->string('file_path')->nullable();
            $table->string('liquidation_bulletin', 20);
            $table->date('liquidation_date');
            $table->string('liquidation_file_path')->nullable();
            $table->string('receipt_number', 30)->unique();
            $table->date('receipt_date');
            $table->string('receipt_file_path')->nullable();
            $table->string('bon_number', 30)->unique();
            $table->date('bon_date');
            $table->string('bon_file_path')->nullable();

            $table->index('number');
            $table->index('liquidation_bulletin');
            $table->index('receipt_number');

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
        Schema::dropIfExists('declarations');
    }
};
