<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SteganoController extends Controller
{
    public function index()
    {
        // Ini akan memanggil file desain HTML kamu nanti
        return view('stegano.index');
    }

    public function caraKerja()
    {
        return view('.cara-kerja');
    }
}