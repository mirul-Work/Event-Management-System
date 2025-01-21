<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;

class AttendeDashboardController extends Controller
{
    public function index(){
        return view('attende.dashboard');

    }

    public function indexEvents(){
        $events = Events::all();
        return view('attende.events.index', compact('events'));
    }
}
