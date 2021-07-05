<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use App\Admin;
use App\Http\Resources\Users as AdminResource;

class AdminController extends Controller
{

    public function index()
    {
        //Get Userdata
        $admin = Admin::paginate(15);

        return AdminResource::collection($admin);
    }

    public function checkEmail($email)
    {
        $user = Admin::where('email', $email)->get();

        return count($user);
    }

    public function checkUser(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = Admin::where('email', $email)->get();
        $body = [];
        if (sizeof($user) > 0) {
            if (password_verify($password, $user[0]['password'])) {
                $response = 'success';
                $body = [ 'id' => $user[0]['id'] ];

            } else {
                $response = 'wrong_credential';
            }
        } else {
            $response = 'not_exist';
        }

        return response()->json([ 'response' => $response, 'body' => $body ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function add(Request $request)
    {
        //first check email
        $email_exits = static::checkEmail($request->input('email'));
        $body = [];
        if ($email_exits == 0) {

            $admin = new Admin;

            $admin->name = (string) $request->input('name');
            $admin->email = (string) $request->input('email');
            $admin->password = (string) password_hash($request->input('password'), PASSWORD_DEFAULT);
            $admin->status = 0;

            if ($admin->save()) {
                $response = 'success';
                $id = $admin->id;
                $body = [ 'id' => $id ];
            } else {
                $response = 'database_error';
            }
        } else {
            $response = 'email_exits';
        }

        return response()->json([ 'response' => $response, 'body' => $body ]);
    }

    public function setData(Request $request)
    {
        $admin = Admin::findOrFail($request->id) ;

        $admin->id = $request->input('id');
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = $request->input('password');
        // $admin->created_at=time();

        if ($admin->save()) {
            return new AdminResource($admin);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::findOrFail($id);

        return new AdminResource($admin);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $admin = Admin::findOrFail($id);
        if ($admin->delete()) {
            return new AdminResource($admin);
        }
    }

}
