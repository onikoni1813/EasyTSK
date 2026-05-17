<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDomain extends Model
{
    protected $fillable = ['domain', 'ad_code_1', 'ad_code_2', 'ad_code_3', 'direct_link', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
