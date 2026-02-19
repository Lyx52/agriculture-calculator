<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmSeason extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function operations(): HasMany {
        return $this->hasMany(FarmlandOperation::class, 'season_id', 'id');
    }
}
