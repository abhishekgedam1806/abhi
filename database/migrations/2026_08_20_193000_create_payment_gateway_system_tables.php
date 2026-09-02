<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaySystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Orders Table
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('order_number', 64)->unique();
                $table->string('payable_type', 100); // App\Company, App\User, App\Business
                $table->unsignedBigInteger('payable_id');
                $table->unsignedInteger('package_id');
                $table->string('package_type', 50)->default('employer'); // employer, job_seeker, business
                $table->string('package_title', 150);
                $table->decimal('package_price', 10, 2)->default(0.00);
                $table->decimal('discount_amount', 10, 2)->default(0.00);
                $table->decimal('tax_amount', 10, 2)->default(0.00);
                $table->decimal('total_amount', 10, 2);
                $table->string('currency', 10)->default('INR');
                $table->enum('status', ['pending', 'processing', 'paid', 'failed', 'cancelled', 'refunded', 'partially_refunded'])->default('pending');
                $table->string('gateway', 50)->default('razorpay');
                $table->string('gateway_order_id', 150)->nullable()->index();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['payable_type', 'payable_id']);
                $table->index('status');
                $table->index('created_at');
            });
        }

        // 2. Payments Table
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('order_id')->index();
                $table->string('payable_type', 100);
                $table->unsignedBigInteger('payable_id');
                $table->string('gateway', 50)->default('razorpay');
                $table->string('gateway_payment_id', 150)->nullable()->index();
                $table->string('gateway_order_id', 150)->nullable()->index();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->default('INR');
                $table->string('payment_method', 50)->nullable(); // upi, card, netbanking, wallet
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
                $table->string('transaction_reference', 150)->nullable();
                $table->text('failure_reason')->nullable();
                $table->longText('raw_response')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index(['payable_type', 'payable_id']);
                $table->index('payment_status');
            });
        }

        // 3. Payment Webhooks Table (to prevent duplicate events)
        if (!Schema::hasTable('payment_webhooks')) {
            Schema::create('payment_webhooks', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('gateway', 50)->default('razorpay');
                $table->string('event_id', 150)->unique();
                $table->string('event_type', 100);
                $table->longText('payload');
                $table->boolean('signature_verified')->default(false);
                $table->boolean('processed')->default(false);
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                $table->index(['gateway', 'event_type']);
            });
        }

        // 4. Payment Refunds Table
        if (!Schema::hasTable('payment_refunds')) {
            Schema::create('payment_refunds', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('payment_id')->index();
                $table->unsignedBigInteger('order_id')->index();
                $table->string('gateway', 50)->default('razorpay');
                $table->string('gateway_refund_id', 150)->nullable()->index();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->default('INR');
                $table->string('status', 50)->default('pending'); // pending, processed, failed
                $table->text('reason')->nullable();
                $table->text('raw_response')->nullable();
                $table->timestamps();
            });
        }

        // 5. Add Razorpay columns to site_settings table if missing
        if (Schema::hasTable('site_settings')) {
            Schema::table('site_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('site_settings', 'is_razorpay_active')) {
                    $table->tinyInteger('is_razorpay_active')->default(1)->nullable();
                }
                if (!Schema::hasColumn('site_settings', 'razorpay_key')) {
                    $table->string('razorpay_key', 255)->nullable();
                }
                if (!Schema::hasColumn('site_settings', 'razorpay_secret')) {
                    $table->string('razorpay_secret', 255)->nullable();
                }
                if (!Schema::hasColumn('site_settings', 'razorpay_webhook_secret')) {
                    $table->string('razorpay_webhook_secret', 255)->nullable();
                }
                if (!Schema::hasColumn('site_settings', 'razorpay_mode')) {
                    $table->enum('razorpay_mode', ['test', 'live'])->default('test')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_refunds');
        Schema::dropIfExists('payment_webhooks');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');

        if (Schema::hasTable('site_settings')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $columns = ['is_razorpay_active', 'razorpay_key', 'razorpay_secret', 'razorpay_webhook_secret', 'razorpay_mode'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('site_settings', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}
