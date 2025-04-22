<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableConfiguration extends Model
{
    protected $casts = [
        'visible_columns' => 'array',
    ];

}
