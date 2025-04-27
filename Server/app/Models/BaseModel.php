<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $attributes = [
        'estado' => 'activo',
    ];
}
