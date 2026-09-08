<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'order_detail_external_id')) {
                $table->string('order_detail_external_id', 36)->nullable()->after('id')->index();
            }
        });

        // Auto-generate UUIDs for any existing order details records
        $existingRecords = DB::table('order_details')
            ->whereNull('order_detail_external_id')
            ->orWhere('order_detail_external_id', '')
            ->select('id')
            ->get();

        foreach ($existingRecords as $record) {
            DB::table('order_details')
                ->where('id', $record->id)
                ->update([
                    'order_detail_external_id' => (string) Str::uuid(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'order_detail_external_id')) {
                $table->dropColumn('order_detail_external_id');
            }
        });
    }
};
