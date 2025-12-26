<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;
use Wildside\Userstamps\Userstamps;

class Opname extends SystemModel
{
    use Userstamps;

    protected $perPage = 20;

    protected $table = 'opname';

    protected $primaryKey = 'opname_id';

    public $timestamps = true;

    protected $filters = [
        'filter',
        'start_date',
        'end_date',
        'customer_code',
    ];

    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    ];

    const CREATED_AT = 'opname_created_at';
    const UPDATED_AT = 'opname_updated_at';
    const DELETED_AT = 'opname_deleted_at';
    const CREATED_BY = 'opname_created_by';
    const UPDATED_BY = 'opname_updated_by';
    const DELETED_BY = 'opname_deleted_by';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'opname_id',
        'opname_mulai',
        'opname_selesai',
        'opname_nama',
        'opname_code_customer',
        'opname_created_at',
        'opname_created_by',
        'opname_updated_at',
        'opname_updated_by',
        'opname_status',
        'opname_capture',
    ];

    public static function field_name()
    {
        return 'opname_nama';
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public function fieldSearching()
    {
        return 'opname_nama';
    }

    public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'opname_code_customer');
    }
}
