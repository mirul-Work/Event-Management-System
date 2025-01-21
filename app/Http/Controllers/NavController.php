<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavController extends Controller
{
    public function ReturnHome()
    {
        // Pass any required data to the view
        return view('home', [
            'title' => 'Home', // Example data
        ]);
    }
}
