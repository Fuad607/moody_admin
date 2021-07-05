<?php

namespace App\Http\Controllers;

use App\Survey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use  App\Http\Requests;
use App\UserSpecialSituation;
use App\Http\Resources\UserSpecialSituation as UserspecialsituationResource;
use Illuminate\Http\Resources\Json\JsonResource as SurveyResource;
use Illuminate\Http\Resources\Json\JsonResource as UserResource;
use Illuminate\Support\Facades\DB;

class UserspecialsituationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($survey_id)
    {
        $user_special_situation = DB::select('SELECT user_special_situation.* FROM `user_special_situation` WHERE user_special_situation.survey_id='.$survey_id);

        return $user_special_situation;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_special_situation = $request->isMethod('put') ? UserSpecialSituation::findOrFail($request->id) : new UserSpecialSituation;

        $user_special_situation->survey_id = $request->input('survey_id');
        $user_special_situation->special_situation = $request->input('special_situation');
        $user_special_situation->special_situation_type =(int) $request->input('special_situation_type');

        if ($user_special_situation->save()) {
            return new UserspecialsituationResource($user_special_situation);
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
        $user_special_situation = UserSpecialSituation::findOrFail($id);

        return new UserspecialsituationResource($user_special_situation);
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
    public function delete($id)
    {
        $user_special_situation =  UserSpecialSituation::findOrFail($id);

        if ($user_special_situation->delete()) {
            return new UserspecialsituationResource($user_special_situation);
        }
    }
}
