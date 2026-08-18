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
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'download_location')) {
                $table->string('download_location', 255)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'download_s3')) {
                $table->unsignedTinyInteger('download_s3')->default(0);
            }
            if (!Schema::hasColumn('order_details', 'download_s3_region')) {
                $table->string('download_s3_region', 100)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'download_s3_bucket_name')) {
                $table->string('download_s3_bucket_name', 255)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'download_s3_access_key_id')) {
                $table->string('download_s3_access_key_id', 255)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'download_s3_secret_access_key')) {
                $table->string('download_s3_secret_access_key', 255)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'download_cdn_url')) {
                $table->string('download_cdn_url', 500)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'subscription')) {
                $table->unsignedTinyInteger('subscription')->default(0)->index();
            }
            if (!Schema::hasColumn('order_details', 'subscription_user_id')) {
                $table->unsignedBigInteger('subscription_user_id')->nullable()->index();
            }
            if (!Schema::hasColumn('order_details', 'subscription_plan_id')) {
                $table->string('subscription_plan_id', 191)->nullable()->index();
            }
            if (!Schema::hasColumn('order_details', 'subscription_provider')) {
                $table->string('subscription_provider', 50)->nullable()->index();
            }
            if (!Schema::hasColumn('order_details', 'subscription_plan_total')) {
                $table->decimal('subscription_plan_total', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'subscription_plan_remaining')) {
                $table->decimal('subscription_plan_remaining', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'subscription_type')) {
                $table->string('subscription_type', 50)->nullable();
            }
            if (!Schema::hasColumn('order_details', 'subscription_status')) {
                $table->string('subscription_status', 50)->nullable()->index();
            }
            if (!Schema::hasColumn('order_details', 'active_subscription')) {
                $table->unsignedTinyInteger('active_subscription')->default(0)->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $cols = [
                'download_location',
                'download_s3',
                'download_s3_region',
                'download_s3_bucket_name',
                'download_s3_access_key_id',
                'download_s3_secret_access_key',
                'download_cdn_url',
                'subscription',
                'subscription_user_id',
                'subscription_plan_id',
                'subscription_provider',
                'subscription_plan_total',
                'subscription_plan_remaining',
                'subscription_type',
                'subscription_status',
                'active_subscription',
            ];

            foreach ($cols as $col) {
                if (Schema::hasColumn('order_details', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
