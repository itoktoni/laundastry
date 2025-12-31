<?php

namespace App\Dao\Models;

use App\Dao\Entities\TransaksiEntity;
use App\Dao\Models\Core\SystemModel;
use App\Models\Scopes\TransaksiScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy(TransaksiScope::class)]
class Kotor extends SystemModel
{
    use TransaksiEntity;

    protected $perPage = 20;
    protected $table = 'view_transaksi';
    protected $primaryKey = 'transaksi_code';

    protected $filters = [
        'filter',
        'start_date',
        'end_date',
        'category_code',
        'customer_code',
    ];

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
