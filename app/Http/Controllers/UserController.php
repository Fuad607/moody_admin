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

class UserController extends Controller
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
        $users = new UsersController();
        $users_result = $users->index();

        return view('user', ['status_nav' => 'user', 'users_result' => $users_result, 'status' => $request->status]);
    }

    public function adduser(Request $request)
    {
        $users = new UsersController();
        $user = json_decode( $users->add($request)->getContent());


        if ($user->response == "email_exits") {
            $return_message['status'] = 4;
        } elseif ($user->response == "success") {
            $return_message['status'] = 5;
        }

        return Redirect::route('user', $return_message);
     }

    public function edituser(Request $request)
    {
        $users = new UsersController();
        $users->setData($request);

        $return_message['status'] = 1;

        return Redirect::route('user', $return_message);
        //return redirect('relationship');
    }

    public function saveuserrelationship(Request $request)
    {
        $user_relationship=new UserrelationshipController();
        $user_relationship->add($request);
        $return_message['status'] = 2;

        return Redirect::route('user', $return_message);
    }

    public function deletecontact(Request $request)
    {
        $user_relationship=new UserrelationshipController();
        $user_relationship->destroy($request->id);
        $return_message['status'] = 3;

        return Redirect::route('user', $return_message);
    }
}
