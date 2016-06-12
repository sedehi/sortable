<?php namespace Sedehi\Sortable;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

trait Sortable
{

    public function scopeSortable($query)
    {
        if (Request::has('sort') && Request::has('order') && $this->columnExists(Request::get('sort'))) {
            return $query->orderBy(Request::get('sort'), Request::get('order'));
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
        $icon  = Config::get('sortable.sortable_icon');

        $parameters = [
            'sort'  => $col,
            'order' => Request::get('order') === 'asc' ? 'desc' : 'asc'
        ];
        $controller = Request::route()->getAction()['controller'];
        $controller = str_replace(app()->getNamespace().'Http\Controllers'.'\\', '', $controller);
        $qs         = http_build_query(array_merge(Request::all(), $parameters));

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
