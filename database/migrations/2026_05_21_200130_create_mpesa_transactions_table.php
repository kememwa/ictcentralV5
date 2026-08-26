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

            /*
            |--------------------------------------------------------------------------
            | Customer Information
            |--------------------------------------------------------------------------
            */

            $table->string('phone_number');

            /*
            |--------------------------------------------------------------------------
            | Order Information
            |--------------------------------------------------------------------------
            */

            $table->string('order_number')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Payment Information
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 10, 2);

            $table->enum('status', [
                'PENDING',
                'SUCCESS',
                'FAILED',
                'CANCELLED',
                'TIMEOUT'
            ])->default('PENDING');

            /*
            |--------------------------------------------------------------------------
            | M-Pesa Request IDs
            |--------------------------------------------------------------------------
            */

            $table->string('merchant_request_id');

            $table->string('checkout_request_id')
                ->unique();

            $table->string('initial_description');

            $table->string('initial_response_code');
            /*
            |--------------------------------------------------------------------------
            | M-Pesa Receipt Details
            |--------------------------------------------------------------------------
            */

            $table->string('initial_transaction_date');

            $table->string('mpesa_receipt_number')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | Callback Result
            |--------------------------------------------------------------------------
            */

            $table->integer('result_code')
                ->nullable();

            $table->text('result_description')
                ->nullable();


            $table->longText('stk_request_payload')
                ->nullable();

            $table->longText('stk_response_payload')
                ->nullable();

            $table->longText('callback_payload')
                ->nullable();
            /*
            |--------------------------------------------------------------------------
            | Processing Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp('paid_at')
                ->nullable();

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