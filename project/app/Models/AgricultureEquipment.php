<?php

namespace App\Models;

use App\Models\Traits\ImplementsAgricultureEquipment;
use Illuminate\Database\Eloquent\Model;

class AgricultureEquipment extends Model
{
    use ImplementsAgricultureEquipment;
    protected $guarded = ['id'];
    public $timestamps = false;


}
