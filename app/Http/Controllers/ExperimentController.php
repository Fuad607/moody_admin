<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UsersController as Users;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class ExperimentController extends Controller
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
    public function index()
    {
         return  view('experiment',['status_nav'=> 'experiment']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $admin = new AdminController();
        $admin_result = json_decode($admin->add($request)->getContent());

        if ($admin_result->response == "email_exits") {
            $return_message['error']=  'This E-Mail already used!';
        } elseif ($admin_result->response == "success") {
            $return_message['status']=  'The user successfully added!';
        }

        $return_message['status_nav']= 'registeruser';

        return view('registeruser',$return_message );
    }
}
