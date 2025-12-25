<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Register
 *
 * @property $register_id
 * @property $register_code
 * @property $register_id_jenis
 * @property $register_qty
 * @property $register_tanggal
 * @property $register_created_at
 * @property $register_created_by
 * @property $register_updated_at
 * @property $register_updated_by
 * @property $register_deleted_at
 * @property $register_deleted_by
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Register extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'detail_register';
    protected $primaryKey = 'register_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['register_id', 'register_code', 'register_code_customer', 'register_id_jenis', 'register_qty', 'register_tanggal', 'register_created_at', 'register_created_by', 'register_updated_at', 'register_updated_by', 'register_deleted_at', 'register_deleted_by'];

    public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'register_code_customer');
    }

    public function has_jenis()
    {
        return $this->hasOne(Jenis::class, 'jenis_id', 'register_id_jenis');
    }
}
