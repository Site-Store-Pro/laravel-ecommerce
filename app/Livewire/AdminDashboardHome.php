<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class AdminDashboardHome extends Component
{
    public bool $confirmingDemoPurge = false;

    public function mount(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrderProcessor()), 403, 'Unauthorized dashboard access.');
    }

    public function getHasDemoContentProperty(): bool
    {
        return DB::table('products')->where('is_demo', 1)->exists();
    }

    public function purgeDemoContent(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->role_id == 3), 403);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Cross-selling records flagged as demo
        DB::table('product_cross_selling')->where('is_demo', 1)->delete();

        // 2. Images for demo variants
        DB::table('product_images')->where('is_demo', 1)->delete();

        // 3. Get all demo variant IDs for cascaded child deletes
        $demoVariantIds = DB::table('product_variants')
            ->where('is_demo', 1)
            ->pluck('id')
            ->toArray();

        if (!empty($demoVariantIds)) {
            // 4. Event rows attached to demo variants
            DB::table('product_variant_events')
                ->whereIn('variant_id', $demoVariantIds)
                ->delete();

            // 5. Inventory rows attached to demo variants
            DB::table('products_inventory')
                ->whereIn('variant_id', $demoVariantIds)
                ->delete();
        }

        // 6. Get all demo product IDs for field/category cascade
        $demoProductIds = DB::table('products')
            ->where('is_demo', 1)
            ->pluck('id')
            ->toArray();

        if (!empty($demoProductIds)) {
            // 7. Product field options (child of product_fields)
            $demoFieldIds = DB::table('product_fields')
                ->whereIn('product_id', $demoProductIds)
                ->pluck('id')
                ->toArray();

            if (!empty($demoFieldIds)) {
                DB::table('product_field_options')
                    ->whereIn('product_field_id', $demoFieldIds)
                    ->delete();
            }

            // 8. Product fields
            DB::table('product_fields')
                ->whereIn('product_id', $demoProductIds)
                ->delete();

            // 9. Category assignments
            DB::table('product_categories_assignments')
                ->whereIn('product_id', $demoProductIds)
                ->delete();
        }

        // 10. Demo variants
        DB::table('product_variants')->where('is_demo', 1)->delete();

        // 11. Demo products
        DB::table('products')->where('is_demo', 1)->delete();

        // 12. Demo brands
        DB::table('product_brands')->where('is_demo', 1)->delete();

        // 13. Demo categories (children first via sort_order desc, then parents)
        DB::table('product_categories')
            ->where('is_demo', 1)
            ->orderByDesc('parent_id')
            ->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->confirmingDemoPurge = false;
        $this->dispatch('toast', message: 'All demo store content has been permanently deleted.', type: 'success');
    }

    public function render(): View
    {
        // Calculate high-level summary metrics
        $totalSales = DB::table('order_payments')
            ->where('payment_status', 1)
            ->sum('payment_amount');

        $totalOrdersCount = DB::table('orders')->count();

        $pendingOrdersCount = DB::table('orders')
            ->whereIn('order_status', [1, 5, 6, 10])
            ->count();

        $customersCount = DB::table('users')
            ->whereIn('role_id', [\App\Enums\UserRole::User->value, \App\Enums\UserRole::Wholesale->value])
            ->count();

        $recentOrders = \App\Models\Order::with('user')
            ->withCount('details')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.admin-dashboard-home', [
            'totalSales' => $totalSales,
            'totalOrdersCount' => $totalOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'customersCount' => $customersCount,
            'recentOrders' => $recentOrders,
        ]);
    }
}
