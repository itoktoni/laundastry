<?php

namespace App\Dao\Models;

use App\Dao\Entities\TransaksiEntity;
use App\Dao\Models\Core\SystemModel;
use App\Dao\Models\Core\User;
use Wildside\Userstamps\Userstamps;

class Packing extends SystemModel
{
    use TransaksiEntity, Userstamps;

    protected $perPage = 20;
    protected $table = 'packing';
    protected $primaryKey = 'packing_id';

    public $incrementing = true;
    public $timestamps = true;

    protected $filters = [
        'filter',
        'start_date',
        'end_date',
    ];

    const CREATED_AT = 'packing_created_at';
    const UPDATED_AT = 'packing_updated_at';
    const CREATED_BY = 'packing_created_by';
    const UPDATED_BY = 'packing_updated_by';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'packing_id',
        'packing_code',
        'packing_id_transaksi',
        'packing_qty',
        'packing_created_at',
        'packing_updated_at',
        'packing_created_by',
        'packing_updated_by',
    ];


    protected function casts(): array
    {
        return [
            'packing_created_at' => 'datetime',
            'packing_updated_at' => 'datetime',
        ];
    }

    public function has_transaksi()
    {
        return $this->hasOne(Transaksi::class, 'transaksi_id', 'packing_id_transaksi');
    }

    public function has_user()
    {
        return $this->hasOne(User::class, 'id', 'packing_created_by');
    }
}
