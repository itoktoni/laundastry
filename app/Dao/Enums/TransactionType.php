<?php

namespace App\Dao\Enums;

use App\Dao\Traits\StatusTrait;
use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum as Enum;

class TransactionType extends Enum implements LocalizedEnum
{
    use StatusTrait;

    public const UNKNOWN = null;
    public const KOTOR = 'KOTOR';
    public const REJECT = 'REJECT';
    public const REWASH = 'REWASH';
    public const BERSIH = 'BERSIH';

}
