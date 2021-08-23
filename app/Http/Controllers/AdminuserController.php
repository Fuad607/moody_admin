<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UsersController as Users;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class AdminuserController extends Controller
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
        $admins = new AdminController();
        $admin_results = $admins->index();

        return view('admin', ['status_nav' => 'admin', 'admin_results' => $admin_results, 'status' => $request->status]);
    }
    public function adminedit(Request $request)
    {
        $admins = new AdminController();
        $admin_result = $admins->show($request->id);

        return view('adminedit', ['status_nav' => 'admin', 'admin_result' => $admin_result, 'status' => $request->status]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
    }

    public function addadmin(Request $request)
    {
        $this->validator($request->all())->validate();

        $admin = new AdminController();
        $admin_result = json_decode($admin->add($request)->getContent());

        if ($admin_result->response == "email_exits") {
            $return_message['status'] = 2;
        } elseif ($admin_result->response == "success") {
            $return_message['status'] = 4;
        }

        return Redirect::route('admin', $return_message);

    }
    public function editadmin(Request $request)
    {
        $admin = new AdminController();
        $admin->setData($request);

        $return_message['status'] = 1;

        return Redirect::route('admin', $return_message);
     }

    public function deleteadmin(Request $request)
    {
        $admin=new AdminController();
        $admin->destroy($request->id);
        $return_message['status'] = 3;

        return Redirect::route('admin', $return_message);
    }
}
