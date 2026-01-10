<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Jenis
 *
 * @property $jenis_id
 * @property $jenis_nama
 * @property $jenis_code_customer
 * @property $jenis_harga
 * @property $jenis_fee
 * @property $jenis_total
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Jenis extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'jenis';
    protected $primaryKey = 'jenis_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['jenis_id', 'jenis_nama', 'jenis_berat', 'jenis_type', 'jenis_code_customer', 'jenis_harga', 'jenis_fee', 'jenis_total'];

    public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'jenis_code_customer');
    }

    public static function field_name()
    {
        return 'jenis_nama';
    }

    public function fieldSearching()
    {
        return 'jenis_nama';
    }
}
