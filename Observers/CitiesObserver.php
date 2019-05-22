<?php

namespace Modules\RegionBuilder\Observers;

use Illuminate\Support\Facades\DB;

use Modules\RegionBuilder\Entities\Cities;

class CitiesObserver 
{
    public function creating(Cities $city) {
        $ret = DB::table($city->getTable())->where('code', $city->province_code)->first();
        if ($ret) {
            $city->pid = $ret->id;
        }
    }
}
