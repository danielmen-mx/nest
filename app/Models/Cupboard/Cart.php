<?php

namespace App\Models\Cupboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuidTrait;

class Cart extends Model
{
    use HasFactory, SoftDeletes, HasUuidTrait;

    protected $fillable = [
        
    ];

    protected $casts = [
        
    ];

    /**
     * Defines the models will ever load with this model
     */
    protected $with = [];

    
}
