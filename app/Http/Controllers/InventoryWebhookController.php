<?php

namespace App\Http\Controllers;

use App\Models\ProductInventory;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryWebhookController extends Controller
{
    /**
     * Handle the incoming inventory update webhook request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        // 1. Authenticate with token
        $configuredToken = config('services.inventory_webhook.token') ?: env('INVENTORY_WEBHOOK_SECRET');
        $requestToken = $request->header('X-Inventory-Webhook-Token') 
            ?: ($request->bearerToken() ?: $request->input('api_token'));

        if (!$configuredToken || $requestToken !== $configuredToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid or missing webhook token.'
            ], 401);
        }

        // 2. Validate request parameters
        $validator = Validator::make($request->all(), [
            'sku' => 'required|string',
            'stock_level' => 'nullable|integer|min:0',
            'warehouse_level' => 'nullable|integer|min:0',
            'use_warehouse_stock' => 'nullable|in:0,1,true,false',
            'location_id' => 'nullable|integer|min:1',
            'warehouse_stocks' => 'nullable|array',
            'warehouse_stocks.*.warehouse_location_id' => 'required_with:warehouse_stocks|integer|exists:warehouse_locations,id',
            'warehouse_stocks.*.stock_level' => 'required_with:warehouse_stocks|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $sku = $request->input('sku');

        // 3. Find variant
        $variant = ProductVariant::where('sku', $sku)->first();
        if (!$variant) {
            return response()->json([
                'status' => 'error',
                'message' => "Variant with SKU '{$sku}' not found."
            ], 404);
        }

        // 4. Update or create inventory details
        $updateData = [];

        if ($request->has('stock_level')) {
            $updateData['quantity_available'] = (int) $request->input('stock_level');
        }

        if ($request->has('warehouse_level')) {
            $updateData['warehouse_stock_level'] = (int) $request->input('warehouse_level');
        }

        if ($request->has('use_warehouse_stock')) {
            $val = $request->input('use_warehouse_stock');
            $updateData['use_warehouse_stock'] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('location_id')) {
            $updateData['location_id'] = (int) $request->input('location_id');
        }

        if (empty($updateData) && !$request->has('warehouse_stocks')) {
            return response()->json([
                'status' => 'error',
                'message' => 'No inventory fields provided for update.'
            ], 400);
        }

        $inventory = ProductInventory::updateOrCreate(
            ['variant_id' => $variant->id],
            $updateData
        );

        // Sync optional child warehouse stocks
        if ($request->has('warehouse_stocks') && is_array($request->input('warehouse_stocks'))) {
            \App\Models\ProductInventoryWarehouse::where('product_inventory_id', $inventory->id)->delete();
            foreach ($request->input('warehouse_stocks') as $wStock) {
                if (!empty($wStock['warehouse_location_id'])) {
                    \App\Models\ProductInventoryWarehouse::create([
                        'product_inventory_id'  => $inventory->id,
                        'warehouse_location_id' => (int) $wStock['warehouse_location_id'],
                        'stock_level'           => (int) ($wStock['stock_level'] ?? 0),
                    ]);
                }
            }
        }

        $inventory->refresh();

        return response()->json([
            'status' => 'success',
            'message' => "Inventory for SKU '{$sku}' updated successfully.",
            'data' => [
                'id' => $inventory->id,
                'variant_id' => $variant->id,
                'sku' => $sku,
                'quantity_available' => $inventory->quantity_available,
                'warehouse_stock_level' => $inventory->warehouse_stock_level,
                'reserved_stock' => $inventory->reserved_stock,
                'use_warehouse_stock' => $inventory->use_warehouse_stock,
                'location_id' => $inventory->location_id,
                'calculated_total' => $inventory->available_stock,
                'warehouse_stocks' => $inventory->warehouseInventories->map(fn($w) => [
                    'warehouse_location_id' => $w->warehouse_location_id,
                    'stock_level' => $w->stock_level,
                ])->values()->all(),
                'updated_at' => $inventory->updated_at,
            ]
        ]);
    }
}
