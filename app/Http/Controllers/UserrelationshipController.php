<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Resources\UserRelationship as UserRelationshipResource;
use App\UserRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserrelationshipController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public static function getAllById($id)
    {
        //Get Userdata
        $users_relationship = DB::select(
            'SELECT user_relationship.id as user_relationship_id,user_relationship.user_id,user_relationship.contacted_user_id,relationship_type.type,users.nickname ,users.email FROM user_relationship
                    LEFT JOIN users on users.id=user_relationship.contacted_user_id
                    LEFT JOIN relationship_type on user_relationship.relationship_type_id=relationship_type.id
                WHERE user_relationship.deleted=0 AND user_relationship.user_id='.$id
        );

        return $users_relationship;
    }
    public static function getAllRelationType()
    {
        //Get Userdata
        $relationship = DB::select(
            'SELECT * FROM relationship_type'
        );

        return $relationship;
    }


    public function add(Request $request)
    {
        $user_relationship =  new UserRelationship;

        $user_relationship->user_id = $request->input('user_id');
        $user_relationship->contacted_user_id = $request->input('contacted_user_id');
        $user_relationship->relationship_type_id = $request->input('relationship_type_id');
        $user_relationship->deleted=0;
        $user_relationship->sync=0;

        if ($user_relationship->save()) {
            return "contacted user added";
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
        $user = UserRelationship::findOrFail($id);

        return new UserRelationshipResource($user);
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
            return new UserRelationshipResource($user);
        }
    }

}
