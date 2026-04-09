<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDashboardLayout extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = [
        'layout' => 'json'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
