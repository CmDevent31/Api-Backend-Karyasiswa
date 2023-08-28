<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PageNotFoundController;

class PageNotFoundController extends Controller
{
    public function index(){
		return view('PageNotFound');
	}
}
