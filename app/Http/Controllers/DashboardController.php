<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Hanya user yang sudah login yang bisa akses
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard');
    }
}
