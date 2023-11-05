<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    public function Company()
    {
        return $this->hasMany('App\Models\Company','id', 'company_id');
    }
}
