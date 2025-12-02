<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


class Kotor extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'kotor';
    protected $primaryKey = 'kotor_code';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['kotor_code', 'kotor_tanggal', 'customer_code', 'customer_nama', 'kotor_status', 'kotor_qty'];

    public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'customer_code');
    }

    public static function field_name()
    {
        return 'kotor_tanggal';
    }

    public static function field_tanggal()
    {
        return self::field_name();
    }

    public function getFieldTanggalAttribute()
    {
        return $this->{self::field_tanggal()};
    }
}
