<?php

namespace App\Http\Controllers;

use App\Http\Resources\Leads as ResourcesLeads;
use App\Models\Leads;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     // returns homepage
    public function index()
    {
        return view('home');
    }

    // returns page not found error page
    public function pageNotFound() {
        return view('errors.404');
    }
}
