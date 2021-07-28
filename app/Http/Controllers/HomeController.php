<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UsersController as Users;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $admin_id = auth()->user()->id;
        $total_experiments = DB::select('SELECT COUNT(*) as total FROM `experiments` WHERE admin_id=' . $admin_id)[0]->total;
        $total_users = DB::select('SELECT COUNT(*) as total FROM `users`' )[0]->total;

        return view('home', ['status_nav' => 'dashboard', 'total_experiments' => $total_experiments, 'total_users' => $total_users, 'status' => $request->status]);


    }
}
