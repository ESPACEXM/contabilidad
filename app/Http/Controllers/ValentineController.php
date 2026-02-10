<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValentineController extends Controller
{
    public function index()
    {
        return view('valentine.index');
    }

    public function answer(Request $request)
    {
        $answer = $request->input('answer');
        
        if ($answer === 'si') {
            return view('valentine.yes');
        } else {
            return view('valentine.no');
        }
    }
}
