<?php

namespace App\Dao\Models;

use App\Dao\Entities\TransaksiEntity;
use App\Dao\Models\Core\SystemModel;

class Detail extends SystemModel
{
    use TransaksiEntity;

    protected $perPage = 20;
    protected $table = 'view_detail';
    protected $primaryKey = 'transaksi_id';

    protected $filters = [
        'filter',
        'start_date',
        'end_date',
        'customer',
        'lokasi_id',
        'jenis_id',
    ];

    protected function casts(): array
    {
        return [
            'transaksi_tanggal' => 'date',
        ];
    }

    public function start_date($query)
    {
        $date = request()->get('start_date');
        if ($date) {
            $query = $query->whereDate($this->field_report(), '>=', $date);
        }

        return $query;
    }

    public function end_date($query)
    {
        $date = request()->get('end_date');

        if ($date) {
            $query = $query->whereDate($this->field_report(), '<=', $date);
        }

        return $query;
    }

    public function customer($query)
    {
        $customer = request()->get('customer');

        if ($customer) {
            $query = $query->where($this->field_customer_code(), $customer);
        }

        return $query;
    }

    public function lokasi($query)
    {
        $lokasi = request()->get('lokasi');

        if ($lokasi) {
            $query = $query->where('lokasi_id', $lokasi);
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

    public function has_lokasi()
    {
        return $this->hasOne(Lokasi::class, 'lokasi_id', 'transaksi_id_lokasi');
    }

    public function has_category()
    {
        return $this->hasOne(Category::class, 'category_id', 'transaksi_code_category');
    }
}
