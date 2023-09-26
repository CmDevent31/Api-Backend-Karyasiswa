<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DataController;

class DataController extends Controller
{
    public function index()
    {
        $data = [];// Ambil data dari database atau operasi lainnya
        return view('frontend.view', compact('data'));
    }
 
}
