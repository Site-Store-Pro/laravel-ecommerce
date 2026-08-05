<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('message', 255);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the 7 default messages
        $defaults = [
            ['message' => 'Temporarily Out Of Stock',                         'sort_order' => 1],
            ['message' => 'Back-Ordered: ETA 2 Weeks',                        'sort_order' => 2],
            ['message' => 'Back-Ordered: ETA 4 Weeks',                        'sort_order' => 3],
            ['message' => 'Item Discontinued',                                 'sort_order' => 4],
            ['message' => 'Event Sold-Out',                                    'sort_order' => 5],
            ['message' => 'Invoice Paid',                                      'sort_order' => 6],
            ['message' => 'Registration is no longer available for this event.','sort_order' => 7],
        ];

        foreach ($defaults as $row) {
            DB::table('product_inventory_alerts')->insert(array_merge($row, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_inventory_alerts');
    }
};
