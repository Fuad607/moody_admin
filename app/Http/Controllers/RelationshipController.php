<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UsersController as Users;
use App\RelationshipType;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\RegistersUsers;

class RelationshipController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

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
        $type = RelationshipType::query()->get();

        return view('relationship', ['status_nav' => 'relationship', 'result_type' => $type, 'status' => $request->status]);
    }

    public function addrelationshiptype(Request $request)
    {
        RelationshipType::create(['type' => (string)$request->type]);
        $return_message['status'] = 2;

        return Redirect::route('relationship', $return_message);
    }

    public function editrelationshiptype(Request $request)
    {
        RelationshipType::where('id', $request->id)->update(['type' => (string)$request->type]);

        $return_message['status'] = 1;

        return Redirect::route('relationship', $return_message);
        //return redirect('relationship');
    }
}
