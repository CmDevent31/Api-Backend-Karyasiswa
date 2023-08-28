<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PrestasiController;

class PrestasiController extends Controller
{
    public function index(){
		return view('Prestasi');
	}
}
