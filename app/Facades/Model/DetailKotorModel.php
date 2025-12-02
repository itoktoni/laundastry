<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class DetailKotorModel extends \App\Dao\Models\DetailKotor
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}