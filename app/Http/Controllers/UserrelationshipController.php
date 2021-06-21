<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Resources\UserRelationship;
use App\Userdata;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserrelationshipController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getAllById($id)
    {
        //Get Userdata
        $users_relationship = DB::select(
            'SELECT user_relationship.user_id,user_relationship.contacted_user_id,relationship_type.type,users.nickname FROM `user_relationship`
                    LEFT JOIN users on users.id=user_relationship.contacted_user_id
                    LEFT JOIN relationship_type on user_relationship.relationship_type_id=relationship_type.id
                WHERE user_relationship.deleted=0 AND user_relationship.user_id='.$id
        );

        return $users_relationship;
    }


    public function store(Request $request)
    {
        $user = $request->isMethod('put') ? Userdata::findOrFail($request->id) : new Userdata;

        $user->id = $request->input('id');
        $user->user_id = $request->input('user_id');
        $user->contacted_user_id = $request->input('contacted_user_id');
        $user->relationship_type_id = $request->input('relationship_type_id');

        if ($user->save()) {
            return new UserdataResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = Userdata::findOrFail($id);

        return new UserdataResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
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
     * @return Response
     */
    public function destroy($id)
    {
        //
        $user = UserRelationship::findOrFail($id);
        if ($user->delete()) {
            return new UserdataResource($user);
        }
    }

}
