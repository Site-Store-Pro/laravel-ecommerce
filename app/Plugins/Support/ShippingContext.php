<?php
namespace App\Plugins\Support;

readonly class ShippingContext {
    public function __construct(
        public string $fromZip,
        public string $toZip,
        public string $toCountry,
        public float $weightLbs,
        public float $declaredValue,
        public array $items = [],
    ) {}
}
