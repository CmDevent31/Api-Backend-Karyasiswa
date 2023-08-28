<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\FAQSHelpController;

class FaqsHelpController extends Controller
{
    public function index(){
		return view('FaqsHelp');
	}
}
