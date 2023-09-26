<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TermsConditionController;

class TermsConditionController extends Controller
{
    public function index(){
		return view('TermsCondition');
	}
}
