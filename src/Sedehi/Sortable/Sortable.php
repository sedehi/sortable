<?php namespace Sedehi\Sortable;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Input;
use Route;
trait Sortable
{

    public function scopeSortable($query)
    {
        if (Input::has('sort') && Input::has('order') && $this->columnExists(Input::get('sort'))) {
            return $query->orderBy(Input::get('sort'), Input::get('order'));
        } else {
            return $query;
        }
    }

    public static function sort(array $parameters)
    {
        if (count($parameters) == 1) {
            $parameters[1] = ucfirst($parameters[0]);
        }
        $col   = $parameters[0];
        $title = $parameters[1];
        $icon  = Config::get('sortable::sortable_icon');

        $parameters = [
            'sort'  => $col,
            'order' => Input::get('order') === 'asc' ? 'desc' : 'asc'
        ];
        $controller = Route::currentRouteAction();
        $qs         = http_build_query(array_merge(Input::all(), $parameters));

        $url = action($controller).'?'.$qs;


        return '<a href="'.$url.'"'.'>'.e($title).'</a>'.' '.'<i class="'.$icon.'"></i>';
    }

    private function columnExists($column)
    {
        if (!isset($this->sortable)) {
            return Schema::hasColumn($this->getTable(), $column);
        } else {
            return in_array($column, $this->sortable);
        }
    }
}
