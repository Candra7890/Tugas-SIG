<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shape extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tb_shapes';
    protected $primaryKey = 'shape_id';
}
