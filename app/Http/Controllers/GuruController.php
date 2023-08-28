<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GuruController;

class GuruController extends Controller
{
    public function index(){
		return view('Guru');
	}
}
