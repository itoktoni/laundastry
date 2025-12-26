<?php

namespace App\Dao\Enums;

use App\Dao\Traits\StatusTrait;
use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum as Enum;

class OpnameType extends Enum implements LocalizedEnum
{
    use StatusTrait;

    public const DIBUAT = null;
    public const PROSES = 'PROSES';
    public const SELESAI = 'SELESAI';

}
