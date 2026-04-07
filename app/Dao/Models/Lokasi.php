<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Lokasi
 *
 * @property $lokasi_id
 * @property $lokasi_nama
 * @property $lokasi_code_customer
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Lokasi extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'lokasi';
    protected $primaryKey = 'lokasi_id';

    protected $filters = [
        'filter',
        'lokasi_code_customer',
        'lokasi_nama',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['lokasi_id', 'lokasi_nama', 'lokasi_code_customer'];

     public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'lokasi_code_customer');
    }

    public static function field_name()
    {
        return 'lokasi_nama';
    }

    public function fieldSearching()
    {
        return 'lokasi_nama';
    }

    public function lokasi_nama($query)
    {
        $name = request()->get('lokasi_nama');
        if ($name) {
            $query = $query->where($this->field_name(), 'like', "%$name%");
        }

        return $query;
    }
}
