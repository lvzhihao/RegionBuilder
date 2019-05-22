<?php

namespace Modules\RegionBuilder\Entities;

use Illuminate\Database\Eloquent\Model;

class Streets extends Areas
{
    public function setAreaCodeAttribute($value) {
        $this->attributes['area_code'] = $value;
    }

    protected $fillable = ['code', 'name', 'provinceCode', 'cityCode', 'areaCode'];
}
