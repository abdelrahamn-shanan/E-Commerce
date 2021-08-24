<?php

namespace App\Http\Enumerations;

use Spatie\Enum\Enum;

final class Manage_Stock extends Enum
{
    const Available = 1;
    const NotAvailable = 0;
}
