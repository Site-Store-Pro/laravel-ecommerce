<?php
namespace App\Plugins\Contracts;
use App\Models\Plugin;

interface DisplayPlugin {
    public function slug(): string;
    public function name(): string;
    // $params = parsed shortcode params key=>value, $plugin = DB Plugin model with settings
    public function render(array $params, Plugin $plugin): string;
}
