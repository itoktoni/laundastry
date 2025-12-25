<?php

namespace App\Dao\Models;

use App\Dao\Entities\TransaksiEntity;
use App\Dao\Models\Core\SystemModel;

class Kotor extends SystemModel
{
    use TransaksiEntity;

    protected $perPage = 20;
    protected $table = 'kotor';
    protected $primaryKey = 'transaksi_code';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['transaksi_code', 'transaksi_status'];

    public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'transaksi_code_customer');
    }

    public function has_jenis()
    {
        return $this->hasOne(Jenis::class, 'jenis_id', 'transaksi_id_jenis');
    }

    public function has_category()
    {
        return $this->hasOne(Category::class, 'category_id', 'transaksi_code_category');
    }
}
