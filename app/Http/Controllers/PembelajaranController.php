<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PembelajaranController;

class PembelajaranController extends Controller
{
    public function index(){
		return view('Pembelajaran');
	}
}
