<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class JenisModel extends \App\Dao\Models\Jenis
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}