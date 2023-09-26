<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\QuotesController;

class QuotesController extends Controller
{
    public function index(){
		return view('Quotes');
	}
}
