<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UsersController as Users;
use App\Experiments;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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
    public function index(Request $request)
    {
        $admin_id = auth()->user()->id;
        $experiments = new ExperimentsController();
        $experiment_results = $experiments->index($admin_id);

        return view('experiment', ['status_nav' => 'experiment', 'experiment_results' => $experiment_results, 'status' => $request->status]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function createexperiment()
    {
        $admin_id = auth()->user()->id;

        $experiment_result = Experiments::create(['admin_id' => $admin_id,]);

        return Redirect::route('experimentedit', ['edit_id' => $experiment_result->id]);
    }

    public function experimentedit(Request $request)
    {
        $admin_id = auth()->user()->id;
        $experiment_result = Experiments::where('id', $request->edit_id)->get();

        $experiment_survey = new ExperimentsController();
        $experiment_survey_results=  $experiment_survey->getAllById($request->edit_id);
        $experiment_survey_result_by_users=  $experiment_survey->getAllByUsers($request->edit_id);

     /*   echo '<pre>';
        print_r($experiment_survey_results);
        exit;*/
        return view('experimentedit', ['status_nav' => 'experiment', 'experiment_result' => $experiment_result[0],
            'experiment_survey_results' => $experiment_survey_results['result'] , 'experiment_survey_result_by_users' => $experiment_survey_result_by_users,
            'label_date' => $experiment_survey_results['label_date']]);
    }

    public function setexperiement(Request $request)
    {
        $admin_id = auth()->user()->id;
        $experiments = new ExperimentsController();
        $users = "";
        if(!empty($request->users )){
            foreach ($request->users as $id => $val) {
                $users .= $id . ", ";
            }
        }

        $users = substr($users, 0, -2);

        $experiments->setData($request);
        $experiments->setUsers($request->id,$users);

        return Redirect::route('experimentedit', ['edit_id' => $request->id,'status'=>1]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $admin = new AdminController();
        $admin_result = json_decode($admin->add($request)->getContent());

        if ($admin_result->response == "email_exits") {
            $return_message['error'] = 'This E-Mail already used!';
        } elseif ($admin_result->response == "success") {
            $return_message['status'] = 'The user successfully added!';
        }

        $return_message['status_nav'] = 'registeruser';

        return view('registeruser', $return_message);
    }
}