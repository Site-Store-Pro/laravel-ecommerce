<?php
namespace App\Providers;

use App\Plugins\Support\PluginManager;
use App\Plugins\Support\ShortcodeProcessor;
use App\Plugins\Display\SlideshowPlugin;
use App\Plugins\Display\FeaturedItemsPlugin;
use App\Plugins\Display\CrossSellListPlugin;
use App\Plugins\Shipping\FedExPlugin;
use App\Plugins\Shipping\UpsPlugin;
use App\Plugins\Shipping\UspsPlugin;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PluginManager::class, function ($app) {
            $manager = new PluginManager();

            // Register built-in plugins
            $manager->register(SlideshowPlugin::class);
            $manager->register(FeaturedItemsPlugin::class);
            $manager->register(CrossSellListPlugin::class);
            $manager->register(FedExPlugin::class);
            $manager->register(UpsPlugin::class);
            $manager->register(UspsPlugin::class);

            // Discover external drop-in plugins from /plugins folder at project root
            $manager->discoverExternalPlugins(base_path('plugins'));

            return $manager;
        });

        $this->app->singleton(ShortcodeProcessor::class, function ($app) {
            return new ShortcodeProcessor($app->make(PluginManager::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
