<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use App\Users;
use App\Http\Resources\Users as UserResource;

class UsersController extends Controller
{
    public function index()
    {
        //Get Userdata
        $users=Users::paginate(15);
        return UserResource::collection($users);
    }

    public function checkEmail($email)
    {
        $user=Users::where('email',$email)->get();

        return count($user);
    }

  public function checkUser(Request $request)
    {
        $email=$request->input('email');
       $password=$request->input('password');

        $user=Users::where('email',$email)->get();

        if(sizeof($user)>0){
            if(password_verify($password,$user[0]['password']))
            {
                return response()->json(array('response' => 'success', 'message' => 'success'));
            }else{
                return response()->json(array('response' => 'not_exist', 'message' => 'error'));
            }
        }else{
            return response()->json(array('response' => 'not_exist', 'message' => 'error'));
        }

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function add(Request $request)
    {
        //first check email
        $email_exits=static ::checkEmail($request->input('email'));
        if($email_exits==0){

            $user = new Users;

            $user->nickname=(string)$request->input('nickname');
            $user->email=$request->input('email');
            $user->password=password_hash($request->input('password'),PASSWORD_DEFAULT);
            $user->timestamp=time();
            $user->location="";
            $user->user_unique_code="";
            $user->msc=0;
            $user->s=0;
            $user->p=0;
            $user->e=0;

            if($user->save()){
                $response='success';
                $id=$user->id;
            }else{
                $response='database_error';
            }
        }else{
            $response='email_exits';
        }

        return response()->json(array('response' => $response,'body'=>['id'=>$id]));
    }

    public function setData(Request $request)
    {
        $user = $request->isMethod('put')? Users::findOrFail($request->id): new Users;

        $user->id=$request->input('id');
        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->password=$request->input('password');
        $user->created_at=time();

        if($user->save()){
            return new UserResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=Users::findOrFail($id);

        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user=Users::findOrFail($id);
        if($user->delete()){
            return new UserResource($user);
        }
    }
}
