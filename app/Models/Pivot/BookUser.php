<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookUser extends Pivot
{
    public static array $statuses = [
        'WANT_TO_READ' => 'want to read',
        'READING' => 'reading',
        'READ' => 'read',
    ];
}
