<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TentangKamiController;

class TentangKamiController extends Controller
{
    public function index(){
		return view('TentangKami');
	}
}