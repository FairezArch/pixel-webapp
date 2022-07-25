<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Age extends Model
{
    use HasFactory, SoftDeletes;

    public function customers()
    {
        return $this->hasMany('App\Models\Customer');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
