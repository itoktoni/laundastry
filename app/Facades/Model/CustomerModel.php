<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class CustomerModel extends \App\Dao\Models\Customer
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}