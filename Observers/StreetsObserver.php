<?php

namespace Modules\RegionBuilder\Observers;

use Illuminate\Support\Facades\DB;

use Modules\RegionBuilder\Entities\Streets;

class StreetsObserver 
{
    public function creating(Streets $street) {
        $ret = DB::table($street->getTable())->where('code', $street->area_code)->first();
        if ($ret) {
            $street->pid = $ret->id;
        }
    }
}
