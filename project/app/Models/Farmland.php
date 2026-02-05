<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmland extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    public function farm(): BelongsTo {
        return $this->belongsTo(Farm::class, 'farm_id', 'id');
    }

    public function crop(): BelongsTo
    {
        return $this->belongsTo(FarmCrop::class, 'crop_id', 'id');
    }

    public function owner(): HasOneThrough {
        return $this->hasOneThrough(User::class, Farm::class, 'id', 'id', 'farm_id', 'owner_id');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(FarmlandOperation::class, 'farmland_id', 'id');
    }
}
