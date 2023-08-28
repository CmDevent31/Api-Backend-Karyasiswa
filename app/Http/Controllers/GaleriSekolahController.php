<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GaleriSekolahController;

class GaleriSekolahController extends Controller
{
    public function index(){
		return view('GaleriSekolah');
	}
}

