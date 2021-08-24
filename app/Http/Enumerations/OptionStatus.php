<?php

namespace App\Http\Enumerations;

use Spatie\Enum\Enum;

final class OptionStatus extends Enum
{
    const Active = 1;
    const NotActive = 0;
}
