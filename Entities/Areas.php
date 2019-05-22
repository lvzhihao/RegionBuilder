<?php

namespace Modules\RegionBuilder\Entities;

use Illuminate\Database\Eloquent\Model;

class Areas extends Cities
{
    public function setCityCodeAttribute($value) {
        $this->attributes['city_code'] = $value;
    }

    protected $fillable = ['code', 'name', 'provinceCode', 'cityCode'];
}
