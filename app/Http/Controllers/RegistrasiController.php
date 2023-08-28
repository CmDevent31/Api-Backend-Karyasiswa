<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RegistrasiController;

class RegistrasiController extends Controller
{
    public function index(){
		return view('Registrasi');
	}
}
