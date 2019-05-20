<?php

namespace Modules\RegionBuilder\Entities;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $fillable = ['code', 'name'];
}
