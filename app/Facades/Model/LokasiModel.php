<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class LokasiModel extends \App\Dao\Models\Lokasi
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}