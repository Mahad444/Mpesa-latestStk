<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('TransactionType')->default('pay');
            $table->string('TransID')->default('pay');
            $table->string('TransTime')->default('pay');
            $table->decimal('TransAmount', 8,2)->default(200);;
            $table->string('BusinessShortCode')->default('pay');
            $table->string('BillRefNumber')->default('pay');
            $table->string('InvoiceNumber')->default('pay');
            $table->decimal('OrgAccountBalance',8,2)->default(200);
            $table->string('ThirdPartyTransID')->default('pay');
            $table->string('MSISDN')->default('pay');
            $table->string('FirstName')->default('Mahad');
            $table->string('MiddleName')->default('Said');
            $table->string('LastName')->default('Ally');
            $table->text('response')->default('response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
