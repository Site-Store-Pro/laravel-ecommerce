<?php
namespace App\Providers;

use App\Plugins\Support\PluginManager;
use App\Plugins\Support\ShortcodeProcessor;
use App\Plugins\Display\SlideshowPlugin;
use App\Plugins\Display\FeaturedItemsPlugin;
use App\Plugins\Display\CrossSellListPlugin;
use App\Plugins\Display\BrandsPlugin;
use App\Plugins\Display\CategoriesPlugin;
use App\Plugins\Display\SiteNewsFlashPlugin;
use App\Plugins\Display\TestimonialsPlugin;
use App\Plugins\Display\SocialIconsPlugin;
use App\Plugins\Display\LiveSearchPlugin;
use App\Plugins\Display\EventsCalendarPlugin;
use App\Plugins\Display\ModalDisplayPlugin;
use App\Plugins\Display\FaqsPlugin;
use App\Plugins\Display\OrderStatusTrackerPlugin;
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
            $manager->register(BrandsPlugin::class);
            $manager->register(CategoriesPlugin::class);
            $manager->register(SiteNewsFlashPlugin::class);
            $manager->register(TestimonialsPlugin::class);
            $manager->register(SocialIconsPlugin::class);
            $manager->register(LiveSearchPlugin::class);
            $manager->register(EventsCalendarPlugin::class);
            $manager->register(ModalDisplayPlugin::class);
            $manager->register(FaqsPlugin::class);
            $manager->register(OrderStatusTrackerPlugin::class);
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
