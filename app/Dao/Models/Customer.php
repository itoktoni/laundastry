<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Class Customer
 *
 * @property $customer_code
 * @property $customer_nama
 * @property $customer_alamat
 * @property $customer_logo
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Customer extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'customer';
    protected $primaryKey = 'customer_code';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['customer_code', 'customer_nama', 'customer_alamat', 'customer_logo'];

    public static function boot()
    {
        parent::saving(function ($model)
        {
            if (request()->hasFile('image')) {
                $file = request()->file('image');
                $name = uploadImage($file, 'customer', 100);
                $model->customer_logo = $name;
            }

        });

        parent::boot();
    }

    public static function field_name()
    {
        return 'customer_nama';
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public function fieldSearching()
    {
        return 'customer_nama';
    }
}
