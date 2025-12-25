<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class RegisterModel extends \App\Dao\Models\Register
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}