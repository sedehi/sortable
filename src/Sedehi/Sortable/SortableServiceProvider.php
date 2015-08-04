<?php namespace Sedehi\Sortable;

use Illuminate\Support\ServiceProvider;

class SortableServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */


    public function boot()
    {
        $this->publishes([__DIR__.'/../../config/sortable.php' => config_path('sortable.php')]);

        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();
        $blade->extend(function ($view, $compiler) {
            $pattern = $compiler->createMatcher('sort');
            $replace = '<?php echo \Sedehi\Sortable\Sortable::sort(array $2);?>';

            return preg_replace($pattern, $replace, $view);
        });
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/sortable.php', 'sortable');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }


}
