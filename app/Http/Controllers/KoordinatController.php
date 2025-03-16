<?php

namespace App\Http\Controllers;

use App\Models\Koordinat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KoordinatController extends Controller
{
    public function __construct()
    {
        $this->Koordinat= new Koordinat();
    }

    public function index()
    {
        return view('home');
    }

    public function titikkoordinat()
    {
        $results=$this->Koordinat->allData();
        return json_encode($results);
    }
    
}