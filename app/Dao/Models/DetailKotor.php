<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class DetailKotor
 *
 * @property $kotor_id
 * @property $kotor_code_scan
 * @property $kotor_code_packing
 * @property $kotor_code_bersih
 * @property $kotor_code_customer
 * @property $kotor_id_category
 * @property $kotor_id_jenis
 * @property $kotor_scan
 * @property $kotor_qc
 * @property $kotor_bersih
 * @property $kotor_pending
 * @property $kotor_tanggal
 * @property $kotor_created_at
 * @property $kotor_created_by
 * @property $kotor_updated_at
 * @property $kotor_updated_by
 * @property $kotor_deleted_at
 * @property $kotor_deleted_by
 * @property $kotor_qc_at
 * @property $kotor_qc_by
 * @property $kotor_bersih_at
 * @property $kotor_bersih_by
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class DetailKotor extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'detail_kotor';
    protected $primaryKey = 'kotor_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['kotor_id', 'kotor_code_scan', 'kotor_code_packing', 'kotor_code_bersih', 'kotor_code_customer', 'kotor_code_category', 'kotor_id_jenis', 'kotor_scan', 'kotor_qc', 'kotor_bersih', 'kotor_pending', 'kotor_tanggal', 'kotor_report', 'kotor_created_at', 'kotor_created_by', 'kotor_updated_at', 'kotor_updated_by', 'kotor_deleted_at', 'kotor_deleted_by', 'kotor_qc_at', 'kotor_qc_by', 'kotor_bersih_at', 'kotor_bersih_by'];

    public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'kotor_code_customer');
    }

    public function has_jenis()
    {
        return $this->hasOne(Jenis::class, 'jenis_id', 'kotor_id_jenis');
    }
}
