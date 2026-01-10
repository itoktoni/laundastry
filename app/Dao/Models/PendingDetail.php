<?php

namespace App\Dao\Models;

use App\Dao\Entities\TransaksiEntity;
use App\Dao\Models\Core\SystemModel;
use App\Dao\Models\Core\User;
use Wildside\Userstamps\Userstamps;

class PendingDetail extends SystemModel
{
    use TransaksiEntity, Userstamps;

    protected $perPage = 20;
    protected $table = 'pending';
    protected $primaryKey = 'pending_id';

    public $incrementing = true;
    public $timestamps = true;

    protected $filters = [
        'filter',
        'start_date',
        'end_date',
    ];

    const CREATED_AT = 'pending_created_at';
    const UPDATED_AT = 'pending_updated_at';
    const CREATED_BY = 'pending_created_by';
    const UPDATED_BY = 'pending_updated_by';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pending_id',
        'pending_code',
        'pending_id_transaksi',
        'pending_qty',
        'pending_created_at',
        'pending_updated_at',
        'pending_created_by',
        'pending_updated_by',
    ];


    protected function casts(): array
    {
        return [
            'pending_created_at' => 'datetime',
            'pending_updated_at' => 'datetime',
        ];
    }

    public function has_transaksi()
    {
        return $this->hasOne(Transaksi::class, 'transaksi_id', 'pending_id_transaksi');
    }

    public function has_user()
    {
        return $this->hasOne(User::class, 'id', 'pending_created_by');
    }
}
