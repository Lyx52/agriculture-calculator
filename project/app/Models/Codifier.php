<?php

namespace App\Models;

use App\Enums\DefinedCodifiers;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Codifier extends Model
{
    protected $guarded = ['id'];

    protected function casts()
    {
        return [
            'value' => 'json:unicode',
        ];
    }

    public function children(): HasMany {
        return $this->hasMany(Codifier::class, 'parent_id');
    }

    public function parent(): BelongsTo {
        return $this->belongsTo(Codifier::class, 'parent_id');
    }

    #[Scope]
    protected function scopeWhereParentCode(Builder $query, DefinedCodifiers $parentCode)
    {
        return $query->whereRelation('parent', 'code', '=', $parentCode);
    }
}
