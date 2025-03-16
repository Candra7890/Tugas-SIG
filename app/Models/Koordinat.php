<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Koordinat extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tb_koordinat';
    protected $primaryKey = 'koordinat_id';

    public function allData(){
        $results = DB::table('tb_koordinat')
            ->select('koordinat_nama','latitude','longitude')
            ->get();
        return $results;
    }
}
