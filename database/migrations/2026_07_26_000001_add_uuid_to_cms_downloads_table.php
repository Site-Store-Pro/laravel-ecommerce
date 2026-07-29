<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_downloads', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Backfill any existing rows with a UUID
        \DB::table('cms_downloads')->whereNull('uuid')->get()->each(function ($row) {
            \DB::table('cms_downloads')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
        });

        // Now make the column non-nullable
        Schema::table('cms_downloads', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('cms_downloads', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
