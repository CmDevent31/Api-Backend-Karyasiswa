<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\KaryaSiswaController;

class KaryaSiswaController extends Controller
{
    public function index(){
		return view('KaryaSiswa');
	}
}
