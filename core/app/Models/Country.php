<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use GlobalStatus;
    protected $casts = ['calling_codes' => 'array'];

    public function operators()
    {
        return $this->hasMany(Operator::class);
    }

    public function operatorGroups()
    {
        return $this->hasMany(OperatorGroup::class);
    }
}
