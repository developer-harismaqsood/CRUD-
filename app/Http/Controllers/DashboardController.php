<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['landing_page']);
    }

    public function landing_page()
    {
        if(Auth::user())
            return redirect('dashboard');

        return view('welcome');
    }
    
    public function index()
    {
        $me = Auth::user();
        return view('pages.dashboard.index',compact('me'));
    }
}