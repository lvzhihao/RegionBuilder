<?php

namespace Modules\RegionBuilder\Entities;

use Illuminate\Database\Eloquent\Model;

class Cities extends Provinces
{
    public function setProvinceCodeAttribute($value) {
        $this->attributes['province_code'] = $value;
    }

    protected $fillable = ['code', 'name', 'provinceCode'];
}
