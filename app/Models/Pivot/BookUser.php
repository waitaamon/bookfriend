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

    public function getActionAttribute(): string
    {
        return match ($this->status) {
            'WANT_TO_READ' => 'wants to read',
            'READING' => 'is reading',
            'READ' => 'has read',
        };
    }
}
