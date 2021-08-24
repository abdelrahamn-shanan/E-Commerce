<?php

namespace App\Http\Enumerations;

use Spatie\Enum\Enum;

final class InStock extends Enum
{
    const Available = 1;
    const NotAvailable = 0;
}
