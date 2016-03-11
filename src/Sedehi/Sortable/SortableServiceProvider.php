<?php namespace Sedehi\Sortable;

use Illuminate\Support\ServiceProvider;
use Blade;
use Illuminate\View\Compilers\BladeCompiler;

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

        Blade::directive('sort', function ($expression) {

            list($field, $title) = explode(',', str_replace(['(', ')', '', "'"], '', $expression));

            return '<?php echo \Sedehi\Sortable\Sortable::sort("'.$field.'","'.$title.'") ?>';
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
