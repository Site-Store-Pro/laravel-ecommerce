<?php
namespace App\Plugins\Contracts;
use App\Models\Plugin;
use App\Plugins\Support\ShippingContext;

interface ShippingPlugin {
    public function slug(): string;
    public function name(): string;
    /**
     * Returns array of rate options:
     * [['label'=>'FedEx Ground','rate'=>12.50,'days'=>3,'code'=>'FEDEX_GROUND'], ...]
     */
    public function getRates(ShippingContext $context, Plugin $plugin): array;
}
