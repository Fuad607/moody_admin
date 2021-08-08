<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Users;
use App\Http\Resources\Users as UserResource;

class UsersController extends Controller
{
    public static function index()
    {
        $where_condition = 'WHERE ';
        //Get Userdata
        $users = DB::select('SELECT users.* FROM users WHERE email!=""  ');

        DB::table('admin')->insert([
                'name' => 'Fuad Shirinli',
                'email' => 'test@test.de',
                'password' => '$2y$10$OjYseuAajEWVV2brrAAHt.FKUEe3atUOtGYG9vheLHoei98EDxyga',
                'status'=>0
            ]
        );

        return $users;
    }

    public static function getAllNotSelectedUsersById($id)
    {
        //Get Userdata
        $users = DB::select(
            'SELECT users.id,users.nickname , users.email FROM users WHERE users.id not in (SELECT user_relationship.contacted_user_id from user_relationship
                    where user_relationship.user_id='.$id.')'
        );

        return $users;
    }

    public function checkEmail($email)
    {
        $user = Users::where('email', $email)->get();

        return count($user);
    }

    public function checkUser(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = Users::where('email', $email)->get();
        $body = [];
        if (sizeof($user) > 0) {
            if (password_verify($password, $user[0]['password'])) {
                $response = 'success';
                $body = ['id' => $user[0]['id']];

            } else {
                $response = 'wrong_credential';
            }
        } else {
            $response = 'not_exist';
        }

        return response()->json(['response' => $response, 'body' => $body]);
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

            $user = new Users;

            $user->nickname = (string)$request->input('nickname');
            $user->email = (string)$request->input('email');
            $user->password = (string)password_hash($request->input('password'), PASSWORD_DEFAULT);
            $user->location = " ";
            $user->user_unique_code = " ";
            $user->timestamp = time();
            $user->msc = 0;
            $user->s = 0;
            $user->p = 0;
            $user->e = 0;

            if ($user->save()) {
                $response = 'success';
                $id = $user->id;
                $body = ['id' => $id];
            } else {
                $response = 'database_error';
            }
        } else {
            $response = 'email_exits';
        }

        return response()->json(['response' => $response, 'body' => $body]);
    }

    public function setData(Request $request)
    {
        $user = Users::findOrFail($request->id);

        $user->id = $request->input('id');
        $user->nickname = $request->input('nickname');
        $user->email = $request->input('email');
        $user->msc = $request->input('msc');
        $user->s = $request->input('s');
        $user->p = $request->input('p');
        $user->e = $request->input('e');
        // $user->created_at=time();

        if ($user->save()) {
            return new UserResource($user);
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
        $user = Users::findOrFail($id);

        return new UserResource();
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
     * @param int $id
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
        $user = Users::findOrFail($id);
        if ($user->delete()) {
            return new UserResource($user);
        }
    }

}
