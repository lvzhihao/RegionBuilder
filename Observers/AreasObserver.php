<?php

namespace Modules\RegionBuilder\Observers;

use Illuminate\Support\Facades\DB;

use Modules\RegionBuilder\Entities\Areas;

class AreasObserver 
{
    public function creating(Areas $area) {
        $ret = DB::table($area->getTable())->where('code', $area->city_code)->first();
        if ($ret) {
            $area->pid = $ret->id;
        }
    }
}
