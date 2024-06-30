<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\TransactionStatusRequestEnum;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_owner');
            $table->string('uuid');
            $table->integer('commerceOrder');
            $table->string('token')->unique();
            $table->integer('method_pay');
            $table->enum('status', [ 'pending', 'approved', 'rejected', 'expired' ])->default(TransactionStatusRequestEnum::PENDING); 
            $table->integer('amount');
            
            $table->timestamps();

            $table->foreign('id_owner')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
