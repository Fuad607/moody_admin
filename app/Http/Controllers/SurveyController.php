<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use  App\Http\Requests;
use App\Survey;
use App\Http\Resources\Survey as SurveyResource;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index($id)
    {
        $survey = DB::select(
            'SELECT survey.* FROM `survey`
                WHERE survey.deleted=0 AND survey.user_id='.$id
        );

        return $survey;
    }

    public function create()
    {
        //
    }

    /**  user_id	mood_level	relaxed_level	timestamp	deleted	sync
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $survey = $request->isMethod('put') ? Survey::findOrFail($request->id) : new Survey;

        $survey->user_id = $request->input('user_id');
        $survey->mood_level = $request->input('mood_level');
        $survey->relaxed_level = $request->input('relaxed_level');
        $survey->timestamp = time();
        $survey->deleted = 0;
        $survey->sync =  (int)$request->input('sync');

        if ($survey->save()) {
            return new SurveyResource($survey);
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
        $survey = Survey::findOrFail($id);

        return new SurveyResource($survey);
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
        $survey =  Survey::findOrFail($id);

        $survey->deleted = 1;

        if ($survey->save()) {
            return new \Illuminate\Http\Resources\Json\JsonResource($survey);
        }
    }
}
