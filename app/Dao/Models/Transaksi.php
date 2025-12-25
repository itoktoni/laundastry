<?php

namespace App\Dao\Models;

use App\Dao\Entities\TransaksiEntity;
use App\Dao\Models\Core\SystemModel;

class Transaksi extends SystemModel
{
    use TransaksiEntity;

    protected $perPage = 20;
    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';

    protected $filters = [
        'filter',
        'start_date',
        'end_date',
    ];

     protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    ];

    const CREATED_AT = 'transaksi_created_at';
    const UPDATED_AT = 'transaksi_updated_at';
    const DELETED_AT = 'transaksi_deleted_at';
    const CREATED_BY = 'transaksi_created_by';
    const UPDATED_BY = 'transaksi_updated_by';
    const DELETED_BY = 'transaksi_deleted_by';

    public $timestamps = true;

    public static function field_name()
    {
        return 'transaksi_code';
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public function fieldSearching()
    {
        return 'transaksi_code';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['transaksi_id', 'transaksi_status', 'transaksi_code_scan', 'transaksi_transaksi', 'transaksi_code_packing', 'transaksi_code_bersih', 'transaksi_code_customer', 'transaksi_code_category', 'transaksi_id_jenis', 'transaksi_scan', 'transaksi_qc', 'transaksi_bersih', 'transaksi_pending', 'transaksi_tanggal', 'transaksi_report', 'transaksi_created_at', 'transaksi_created_by', 'transaksi_updated_at', 'transaksi_updated_by', 'transaksi_deleted_at', 'transaksi_deleted_by', 'transaksi_qc_at', 'transaksi_qc_by', 'transaksi_bersih_at', 'transaksi_bersih_by'];

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
