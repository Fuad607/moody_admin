<?php

namespace App\Http\Controllers;

use App\Survey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use  App\Http\Requests;
use App\UserMeeting;
use App\Http\Resources\UserMeeting as UsermeetingResource;
use Illuminate\Http\Resources\Json\JsonResource as SurveyResource;
use Illuminate\Http\Resources\Json\JsonResource as UserResource;
use Illuminate\Support\Facades\DB;

class UsermeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user_meeting = DB::select('SELECT user_meeting.* FROM `user_meeting` WHERE user_meeting.user_id='.$id);

        return $user_meeting;
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
        $user_meeting = $request->isMethod('put') ? UserMeeting::findOrFail($request->id) : new UserMeeting;

        $user_meeting->survey_id = $request->input('survey_id');
        $user_meeting->contacted_user_id = $request->input('contacted_user_id');
        $user_meeting->meeting_type = $request->input('meeting_type');

        if ($user_meeting->save()) {
            return new UsermeetingResource($user_meeting);
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
        $user_meeting = UserMeeting::findOrFail($id);

        return new UsermeetingResource($user_meeting);
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
        $user_meeting =  UserMeeting::findOrFail($id);

        if ($user_meeting->delete()) {
            return new UsermeetingResource($user_meeting);
        }
    }
}
