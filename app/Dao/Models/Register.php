<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;
use App\Models\Scopes\RegisterScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy(RegisterScope::class)]
class Register extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'register';
    protected $primaryKey = 'register_id';

    protected $filters = [
        'filter',
        'start_date',
        'end_date',
        'customer_code',
    ];

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public function field_tanggal()
    {
        return 'register_tanggal';
    }

    public function field_customer()
    {
        return 'register_code_customer';
    }

    public function getFieldTanggalAttribute()
    {
        return $this->{$this->field_tanggal()};
    }

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

    public function customer_code($query)
    {
        $customer = request()->get('customer_code');

        if ($customer) {
            $query = $query->where($this->field_customer(), $customer);
        }

        return $query;
    }

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
