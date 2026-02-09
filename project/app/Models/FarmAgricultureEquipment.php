<?php

namespace App\Models;

use App\Models\Traits\ImplementsAgricultureEquipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmAgricultureEquipment extends Model
{
    use ImplementsAgricultureEquipment;
    use SoftDeletes;
    protected $guarded = ['id'];
    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
