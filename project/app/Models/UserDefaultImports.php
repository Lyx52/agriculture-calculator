<?php

namespace App\Models;

use App\Enums\DefaultImports;
use Illuminate\Database\Eloquent\Model;

class UserDefaultImports extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $casts = [
        'imports' => 'json:unicode',
        'import_type' => DefaultImports::class,
    ];
}
