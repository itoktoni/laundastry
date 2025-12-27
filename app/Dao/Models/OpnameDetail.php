<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;
use App\Models\Scopes\OpnameDetailScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Wildside\Userstamps\Userstamps;

#[ScopedBy(OpnameDetailScope::class)]
class OpnameDetail extends SystemModel
{
    use Userstamps;

    protected $perPage = 20;

    protected $table = 'opname_detail';

    protected $primaryKey = 'odetail_id';

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

    const CREATED_AT = 'odetail_created_at';
    const UPDATED_AT = 'odetail_updated_at';
    const DELETED_AT = 'odetail_deleted_at';
    const CREATED_BY = 'odetail_created_by';
    const UPDATED_BY = 'odetail_updated_by';
    const DELETED_BY = 'odetail_deleted_by';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'odetail_id',
        'odetail_code',
        'odetail_id_opname',
        'odetail_id_jenis',
        'odetail_code_customer',
        'odetail_register',
        'odetail_ketemu',
    ];

    public static function field_name()
    {
        return 'odetail_code_customer';
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public function fieldSearching()
    {
        return 'odetail_code_customer';
    }

    public function has_customer()
    {
        return $this->hasOne(Customer::class, 'customer_code', 'odetail_code_customer');
    }

    public function has_jenis()
    {
        return $this->hasOne(Jenis::class, 'jenis_id', 'odetail_id_jenis');
    }

    public function has_opname()
    {
        return $this->hasOne(Opname::class, 'opname_id', 'odetail_id_opname');
    }
}
