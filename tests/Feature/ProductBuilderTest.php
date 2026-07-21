<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductField;
use App\Models\ProductFieldOption;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\ShoppingCartLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customization_fields_can_be_associated_to_product(): void
    {
        $product = Product::create([
            'title' => 'Custom Cup',
            'seo_slug' => 'custom-cup',
        ]);

        $field = ProductField::create([
            'product_id' => $product->id,
            'label' => 'Engraving',
            'field_type' => 'text',
            'is_required' => true,
            'sort_order' => 1
        ]);

        $this->assertDatabaseHas('product_fields', [
            'product_id' => $product->id,
            'label' => 'Engraving',
            'field_type' => 'text',
            'is_required' => 1
        ]);
    }

    public function test_customization_options_price_modifiers(): void
    {
        $product = Product::create([
            'title' => 'Custom Shirt',
            'seo_slug' => 'custom-shirt',
        ]);

        $field = ProductField::create([
            'product_id' => $product->id,
            'label' => 'Size',
            'field_type' => 'select',
            'is_required' => true,
            'sort_order' => 1
        ]);

        $option = ProductFieldOption::create([
            'product_field_id' => $field->id,
            'option_value' => 'XXL',
            'option_price_modifier' => 5.00,
            'option_wholesale_price_modifier' => 3.00,
            'sort_order' => 1
        ]);

        $this->assertDatabaseHas('product_field_options', [
            'product_field_id' => $field->id,
            'option_value' => 'XXL',
            'option_price_modifier' => 5.00,
            'option_wholesale_price_modifier' => 3.00
        ]);
    }
}
