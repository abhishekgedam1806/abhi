<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersAndCouponsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Offers / Campaigns Table
        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);
                $table->text('description')->nullable();
                $table->string('audience_type', 50)->default('all'); // all, new_users, existing_users
                $table->dateTime('starts_at')->nullable();
                $table->dateTime('expires_at')->nullable();
                $table->string('status', 30)->default('active'); // active, scheduled, disabled, expired
                $table->unsignedInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 2. Coupons Table
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('offer_id')->nullable()->index();
                $table->string('code', 50)->unique();
                $table->string('discount_type', 20)->default('percentage'); // percentage, fixed
                $table->decimal('discount_value', 10, 2)->default(0.00);
                $table->decimal('max_discount', 10, 2)->nullable();
                $table->decimal('min_order_value', 10, 2)->nullable();
                $table->text('applicable_packages')->nullable(); // JSON or comma-separated package IDs
                $table->text('applicable_user_types')->nullable(); // JSON: ['employer', 'job_seeker', 'business', 'all']
                $table->boolean('is_first_purchase_only')->default(false);
                $table->integer('total_usage_limit')->nullable();
                $table->integer('per_user_usage_limit')->default(1);
                $table->integer('used_count')->default(0);
                $table->dateTime('starts_at')->nullable();
                $table->dateTime('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        // 3. Coupon Redemptions Table
        if (!Schema::hasTable('coupon_redemptions')) {
            Schema::create('coupon_redemptions', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('coupon_id')->index();
                $table->unsignedInteger('offer_id')->nullable()->index();
                $table->string('payable_type', 100);
                $table->unsignedInteger('payable_id')->index();
                $table->unsignedInteger('package_id')->nullable()->index();
                $table->unsignedInteger('order_id')->nullable()->index();
                $table->string('payment_id', 150)->nullable();
                $table->string('discount_type', 20)->default('percentage');
                $table->decimal('discount_value', 10, 2)->default(0.00);
                $table->decimal('discount_amount', 10, 2)->default(0.00);
                $table->decimal('original_amount', 10, 2)->default(0.00);
                $table->decimal('final_amount', 10, 2)->default(0.00);
                $table->string('status', 30)->default('completed'); // completed, refunded
                $table->dateTime('redeemed_at')->nullable();
                $table->timestamps();
            });
        }

        // 4. Add coupon fields to orders table if not existing
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'coupon_id')) {
                    $table->unsignedInteger('coupon_id')->nullable()->after('package_title');
                }
                if (!Schema::hasColumn('orders', 'coupon_code')) {
                    $table->string('coupon_code', 50)->nullable()->after('coupon_id');
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
        Schema::dropIfExists('coupon_redemptions');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('offers');
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'coupon_id')) {
                    $table->dropColumn('coupon_id');
                }
                if (Schema::hasColumn('orders', 'coupon_code')) {
                    $table->dropColumn('coupon_code');
                }
            });
        }
    }
}
