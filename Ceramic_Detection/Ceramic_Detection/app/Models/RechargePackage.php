<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargePackage extends Model
{
    protected $fillable = ['amount', 'tokens', 'description', 'is_active'];
}