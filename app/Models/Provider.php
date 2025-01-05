<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_active',
        'endpoint_url',
        'field_mappings',
    ];
}
