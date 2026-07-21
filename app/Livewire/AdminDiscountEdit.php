<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountType;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminDiscountEdit extends Component
{
    public ?int $discountId = null;
    public ?Discount $discount = null;

    // Form fields
    public int $discount_type_id = 1;
    public int $value_type = 2; // 1 = Specific Value, 2 = Percent Off
    public float $order_minimum = 0.00;
    public float $order_maximum = 100000.00;
    public int $order_qty_min = 1;
    public int $order_qty_max = 1000000;
    public int $product_id = 0;
    public string $name = '';
    public float $value = 0.00;
    public ?string $code = null;
    public int $code_type = 0; // 0 = Coupon Code, 1 = Gift Certificate
    public int $free_range1 = 0;
    public int $free_range2 = 0;
    public float $free_percent = 100.00;
    public int $show_get_x_free = 0;
    public ?string $show_get_x_text = null;
    public int $buy_x_get_y = 0;
    public int $product_id_y = 0;
    public float $product_y_percent = 100.00;
    public ?string $start_date = null;
    public ?string $expiration_date = null;
    public int $is_active = 1;
    public int $brand_id = 0;
    public int $brand_qty_min = 1;
    public int $brand_qty_max = 1000000;
    public float $brand_subtotal_min = 0.00;
    public float $brand_subtotal_max = 1000000.00;
    public int $category_id = 0;
    public int $cat_qty_min = 1;
    public int $cat_qty_max = 1000000;
    public float $cat_subtotal_min = 0.00;
    public float $cat_subtotal_max = 1000000.00;
    public int $subcat_id = 0;
    public int $subcat_qty_min = 1;
    public int $subcat_qty_max = 1000000;
    public float $subcat_subtotal_min = 0.00;
    public float $subcat_subtotal_max = 1000000.00;
    public int $style_id = 0;
    public int $style_qty_min = 1;
    public int $style_qty_max = 1000000;
    public float $style_subtotal_min = 0.00;
    public float $style_subtotal_max = 1000000.00;
    public int $item_qty_min = 1;
    public int $item_qty_max = 1000000;
    public float $item_subtotal_min = 0.00;
    public float $item_subtotal_max = 1000000.00;
    public ?string $bogo_cart_text = null;
    public int $free_shipping = 0;
    public int $wholesale_only = 0;
    public float $order_weight_min = 0.00;
    public float $order_weight_max = 1000000.00;

    // Live Search Properties
    public string $productSearch = '';
    public string $brandSearch = '';
    public string $categorySearch = '';
    public string $triggerProductSearch = '';
    public string $targetProductSearch = '';

    public function selectProduct(int $id, string $title): void
    {
        $this->product_id = $id;
        $this->productSearch = $title;
    }

    public function clearProduct(): void
    {
        $this->product_id = 0;
        $this->productSearch = '';
    }

    public function selectTriggerProduct(int $id, string $title): void
    {
        $this->buy_x_get_y = $id;
        $this->triggerProductSearch = $title;
    }

    public function clearTriggerProduct(): void
    {
        $this->buy_x_get_y = 0;
        $this->triggerProductSearch = '';
    }

    public function selectTargetProduct(int $id, string $title): void
    {
        $this->product_id_y = $id;
        $this->targetProductSearch = $title;
    }

    public function clearTargetProduct(): void
    {
        $this->product_id_y = 0;
        $this->targetProductSearch = '';
    }

    public function selectBrand(int $id, string $name): void
    {
        $this->brand_id = $id;
        $this->brandSearch = $name;
    }

    public function clearBrand(): void
    {
        $this->brand_id = 0;
        $this->brandSearch = '';
    }

    public function selectCategory(int $id, string $name): void
    {
        $this->category_id = $id;
        $this->categorySearch = $name;
    }

    public function clearCategory(): void
    {
        $this->category_id = 0;
        $this->categorySearch = '';
    }

    public function mount(?int $id = null): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        \DB::table('discount_types')->where('id', 5)->update(['name' => 'Brand or Category']);

        if ($id) {
            $this->discountId = $id;
            $this->discount = Discount::findOrFail($id);

            // Fill form fields
            $this->discount_type_id = (int) $this->discount->discount_type_id;
            $this->value_type = (int) $this->discount->value_type;
            $this->order_minimum = (float) $this->discount->order_minimum;
            $this->order_maximum = (float) $this->discount->order_maximum;
            $this->order_qty_min = (int) $this->discount->order_qty_min;
            $this->order_qty_max = (int) $this->discount->order_qty_max;
            $this->product_id = (int) $this->discount->product_id;
            $this->name = (string) $this->discount->name;
            $this->value = (float) $this->discount->value;
            $this->code = $this->discount->code;
            $this->code_type = (int) $this->discount->code_type;
            $this->free_range1 = (int) $this->discount->free_range1;
            $this->free_range2 = (int) $this->discount->free_range2;
            $this->free_percent = (float) $this->discount->free_percent;
            $this->show_get_x_free = (int) $this->discount->show_get_x_free;
            $this->show_get_x_text = $this->discount->show_get_x_text;
            $this->buy_x_get_y = (int) $this->discount->buy_x_get_y;
            $this->product_id_y = (int) $this->discount->product_id_y;
            $this->product_y_percent = (float) $this->discount->product_y_percent;
            $this->start_date = $this->discount->start_date ? $this->discount->start_date->format('Y-m-d') : null;
            $this->expiration_date = $this->discount->expiration_date ? $this->discount->expiration_date->format('Y-m-d') : null;
            $this->is_active = (int) $this->discount->is_active;
            $this->brand_id = (int) $this->discount->brand_id;
            $this->brand_qty_min = (int) $this->discount->brand_qty_min;
            $this->brand_qty_max = (int) $this->discount->brand_qty_max;
            $this->brand_subtotal_min = (float) $this->discount->brand_subtotal_min;
            $this->brand_subtotal_max = (float) $this->discount->brand_subtotal_max;
            $this->category_id = (int) $this->discount->category_id;
            $this->cat_qty_min = (int) $this->discount->cat_qty_min;
            $this->cat_qty_max = (int) $this->discount->cat_qty_max;
            $this->cat_subtotal_min = (float) $this->discount->cat_subtotal_min;
            $this->cat_subtotal_max = (float) $this->discount->cat_subtotal_max;
            $this->subcat_id = (int) $this->discount->subcat_id;
            $this->subcat_qty_min = (int) $this->discount->subcat_qty_min;
            $this->subcat_qty_max = (int) $this->discount->subcat_qty_max;
            $this->subcat_subtotal_min = (float) $this->discount->subcat_subtotal_min;
            $this->subcat_subtotal_max = (float) $this->discount->subcat_subtotal_max;
            $this->style_id = (int) $this->discount->style_id;
            $this->style_qty_min = (int) $this->discount->style_qty_min;
            $this->style_qty_max = (int) $this->discount->style_qty_max;
            $this->style_subtotal_min = (float) $this->discount->style_subtotal_min;
            $this->style_subtotal_max = (float) $this->discount->style_subtotal_max;
            $this->item_qty_min = (int) $this->discount->item_qty_min;
            $this->item_qty_max = (int) $this->discount->item_qty_max;
            $this->item_subtotal_min = (float) $this->discount->item_subtotal_min;
            $this->item_subtotal_max = (float) $this->discount->item_subtotal_max;
            $this->bogo_cart_text = $this->discount->bogo_cart_text;
            $this->free_shipping = (int) $this->discount->free_shipping;
            $this->wholesale_only = (int) $this->discount->wholesale_only;
            $this->order_weight_min = (float) $this->discount->order_weight_min;
            $this->order_weight_max = (float) $this->discount->order_weight_max;

            // Populate live search values
            if ($this->product_id) {
                $p = Product::find($this->product_id);
                if ($p) $this->productSearch = $p->title;
            }
            if ($this->brand_id) {
                $b = Brand::find($this->brand_id);
                if ($b) $this->brandSearch = $b->name;
            }
            if ($this->category_id) {
                $c = Category::find($this->category_id);
                if ($c) $this->categorySearch = $c->name;
            }
            if ($this->buy_x_get_y) {
                $p = Product::find($this->buy_x_get_y);
                if ($p) $this->triggerProductSearch = $p->title;
            }
            if ($this->product_id_y) {
                $p = Product::find($this->product_id_y);
                if ($p) $this->targetProductSearch = $p->title;
            }
        }
    }

    public function save()
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $rules = [
            'name' => 'required|string|max:255',
            'discount_type_id' => 'required|exists:discount_types,id',
            'value_type' => 'required|in:1,2',
            'value' => 'required|numeric|min:0',
            'code' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'expiration_date' => 'nullable|date|after_or_equal:start_date',
        ];

        // Additional validations based on discount type
        if ($this->discount_type_id == 1) { // Coupon Code
            $rules['code'] = 'required|string|max:255';
        }

        $this->validate($rules);

        $data = [
            'discount_type_id' => $this->discount_type_id,
            'value_type' => $this->value_type,
            'order_minimum' => $this->order_minimum,
            'order_maximum' => $this->order_maximum,
            'order_qty_min' => $this->order_qty_min,
            'order_qty_max' => $this->order_qty_max,
            'product_id' => $this->product_id,
            'name' => $this->name,
            'value' => $this->value,
            'code' => $this->discount_type_id == 1 ? $this->code : null,
            'code_type' => $this->code_type,
            'free_range1' => $this->free_range1,
            'free_range2' => $this->free_range2,
            'free_percent' => $this->free_percent,
            'show_get_x_free' => $this->show_get_x_free,
            'show_get_x_text' => $this->show_get_x_text,
            'buy_x_get_y' => $this->buy_x_get_y,
            'product_id_y' => $this->product_id_y,
            'product_y_percent' => $this->product_y_percent,
            'start_date' => $this->start_date ?: null,
            'expiration_date' => $this->expiration_date ?: null,
            'is_active' => $this->is_active,
            'brand_id' => $this->brand_id,
            'brand_qty_min' => $this->brand_qty_min,
            'brand_qty_max' => $this->brand_qty_max,
            'brand_subtotal_min' => $this->brand_subtotal_min,
            'brand_subtotal_max' => $this->brand_subtotal_max,
            'category_id' => $this->category_id,
            'cat_qty_min' => $this->cat_qty_min,
            'cat_qty_max' => $this->cat_qty_max,
            'cat_subtotal_min' => $this->cat_subtotal_min,
            'cat_subtotal_max' => $this->cat_subtotal_max,
            'subcat_id' => $this->subcat_id,
            'subcat_qty_min' => $this->subcat_qty_min,
            'subcat_qty_max' => $this->subcat_qty_max,
            'subcat_subtotal_min' => $this->subcat_subtotal_min,
            'subcat_subtotal_max' => $this->subcat_subtotal_max,
            'style_id' => $this->style_id,
            'style_qty_min' => $this->style_qty_min,
            'style_qty_max' => $this->style_qty_max,
            'style_subtotal_min' => $this->style_subtotal_min,
            'style_subtotal_max' => $this->style_subtotal_max,
            'item_qty_min' => $this->item_qty_min,
            'item_qty_max' => $this->item_qty_max,
            'item_subtotal_min' => $this->item_subtotal_min,
            'item_subtotal_max' => $this->item_subtotal_max,
            'bogo_cart_text' => $this->bogo_cart_text,
            'free_shipping' => $this->free_shipping,
            'wholesale_only' => $this->wholesale_only,
            'order_weight_min' => $this->order_weight_min,
            'order_weight_max' => $this->order_weight_max,
        ];

        if ($this->discountId) {
            $this->discount->update($data);
            session()->flash('status', 'Discount updated successfully.');
        } else {
            Discount::create($data);
            session()->flash('status', 'Discount created successfully.');
        }

        return $this->redirectRoute('admin.discounts.index', navigate: true);
    }

    public function render(): View
    {
        $discountTypes = DiscountType::all();
        
        $searchedProducts = [];
        if (strlen($this->productSearch) >= 2) {
            $searchedProducts = Product::where('title', 'like', '%' . $this->productSearch . '%')
                ->limit(25)
                ->get();
        }

        $searchedTriggerProducts = [];
        if (strlen($this->triggerProductSearch) >= 2) {
            $searchedTriggerProducts = Product::where('title', 'like', '%' . $this->triggerProductSearch . '%')
                ->limit(25)
                ->get();
        }

        $searchedTargetProducts = [];
        if (strlen($this->targetProductSearch) >= 2) {
            $searchedTargetProducts = Product::where('title', 'like', '%' . $this->targetProductSearch . '%')
                ->limit(25)
                ->get();
        }

        $searchedBrands = [];
        if (strlen($this->brandSearch) >= 2) {
            $searchedBrands = Brand::where('name', 'like', '%' . $this->brandSearch . '%')
                ->limit(25)
                ->get();
        }

        $searchedCategories = [];
        if (strlen($this->categorySearch) >= 2) {
            $searchedCategories = Category::where('name', 'like', '%' . $this->categorySearch . '%')
                ->limit(25)
                ->get();
        }

        return view('livewire.admin-discount-edit', [
            'discountTypes' => $discountTypes,
            'searchedProducts' => $searchedProducts,
            'searchedTriggerProducts' => $searchedTriggerProducts,
            'searchedTargetProducts' => $searchedTargetProducts,
            'searchedBrands' => $searchedBrands,
            'searchedCategories' => $searchedCategories,
        ]);
    }
}
