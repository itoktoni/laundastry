<?php

namespace App\Dao\Models;

use App\Dao\Entities\TransaksiEntity;
use App\Dao\Models\Core\SystemModel;
use App\Models\Scopes\TransaksiScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use OwenIt\Auditing\Auditable;
use Wildside\Userstamps\Userstamps;

#[ScopedBy(TransaksiScope::class)]
class Transaksi extends SystemModel implements \OwenIt\Auditing\Contracts\Auditable
{
    use TransaksiEntity, Userstamps, Auditable;

    protected $perPage = 20;
    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';

    public $incrementing = true;

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

    protected $auditInclude = [
        'transaksi_id_jenis',
        'transaksi_code_customer',
        'transaksi_scan',
        'transaksi_qc',
        'transaksi_bersih',
        'transaksi_pending',
        'transaksi_bayar',
        'transaksi_tanggal',
        'transaksi_report',
    ];

    public function generateTags(): array
    {
        return [
            $this->has_jenis->jenis_nama,
            $this->has_customer->customer_nama,
        ];
    }

    protected function casts(): array
    {
        return [
            'transaksi_tanggal' => 'date',
            'transaksi_report' => 'date',
            'transaksi_update_at' => 'datetime',
            'transaksi_created_at' => 'datetime',
            'transaksi_qc_at' => 'datetime',
            'transaksi_bersih_at' => 'datetime',
            'transaksi_pending_at' => 'datetime',
        ];
    }

    const CREATED_AT = 'transaksi_created_at';
    const UPDATED_AT = 'transaksi_updated_at';
    const DELETED_AT = 'transaksi_deleted_at';
    const CREATED_BY = 'transaksi_created_by';
    const UPDATED_BY = 'transaksi_updated_by';
    const DELETED_BY = 'transaksi_deleted_by';

    public $timestamps = true;

    public function start_date($query)
    {
        $date = request()->get('start_date');
        if ($date) {
            $query = $query->whereDate($this->field_tanggal(), '>=', $date);
        }

        return $query;
    }

    public function end_date($query)
    {
        $date = request()->get('end_date');

        if ($date) {
            $query = $query->whereDate($this->field_tanggal(), '<=', $date);
        }

        return $query;
    }

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
    protected $fillable = ['transaksi_id', 'transaksi_status', 'transaksi_code_scan', 'transaksi_code_pending', 'transaksi_bayar', 'transaksi_transaksi', 'transaksi_code_packing', 'transaksi_code_bersih', 'transaksi_code_customer', 'transaksi_code_category', 'transaksi_id_jenis', 'transaksi_scan', 'transaksi_qc', 'transaksi_bersih', 'transaksi_pending', 'transaksi_tanggal', 'transaksi_report', 'transaksi_created_at', 'transaksi_created_by', 'transaksi_updated_at', 'transaksi_updated_by', 'transaksi_deleted_at', 'transaksi_deleted_by', 'transaksi_qc_at', 'transaksi_qc_by', 'transaksi_bersih_at', 'transaksi_bersih_by', 'transaksi_pending_at', 'transaksi_pending_by'];

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

    public function has_detail()
    {
        return $this->hasMany(PendingDetail::class, 'pending_id_transaksi', 'transaksi_id');
    }
}
