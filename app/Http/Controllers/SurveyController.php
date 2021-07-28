<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use  App\Http\Requests;
use App\Survey;
use App\Http\Resources\Survey as SurveyResource;
use Illuminate\Support\Facades\DB;

class   SurveyController extends Controller
{
    public function index($user_id,$start,$end)
    {
        if($start!=0 && $start!="" && $end!=0 && $end!=""){
            $time_condition=" AND timestamp>=".$start." AND timestamp<=".$end;
        }

        $survey = DB::select(
            'SELECT survey.* FROM `survey`
                WHERE survey.deleted=0 AND survey.user_id='.$user_id.$time_condition
        );

        return $survey;
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

    public static function getAllById($id,$contacted_user_ids,$start,$end)
    {
        $user_relationship = DB::select('SELECT user_relationship.id,user_relationship.contacted_user_id FROM `user_relationship`
     WHERE user_relationship.deleted=0 AND user_relationship.user_id=' . $id);
        $return_array = [];

        $label_date = "";
        foreach ($user_relationship as $id) {
            //
            $users = DB::select('SELECT * FROM users WHERE users.id=' . $id);
            $start_date = explode("/", $start);

            $start_timestamp= mktime(0, 0, 0, date("m",$start_date[1]), date("d",$start_date[0]), date("Y",$start_date[2]));
            $end_time = mktime(23, 59, 59, date("m", $start_date[1]), date("d", $start_date[0]), date("Y", $start_date[2]));

            $end_date = explode("/", $end);
            $end_timestamp = mktime(0, 0, 0, date("m",$end_date[1]), date("d",$end_date[0]), date("Y",$end_date[2]));

            $mood_level = "";
            $relaxed_level = "";
            $nickname = "";

            while ($start_timestamp <= $end_timestamp) {

                $survey_result = DB::select(
                    'SELECT avg(survey.mood_level-5) as mood_level, avg(survey.relaxed_level-5) as relaxed_level,
                               survey.user_id , users.nickname FROM `survey`
                               LEFT JOIN users on users.id=survey.user_id
                   WHERE users.id =' . $id . '  AND survey.timestamp >=' . $start_timestamp . '  AND survey.timestamp<=' . $end_time . ' Group by user_id ,nickname'
                );

                $nickname = $users[0]->nickname;
                if (!empty($survey_result)) {
                    $mood = round($survey_result[0]->mood_level);
                    $relaxed = round($survey_result[0]->relaxed_level);

                } else {
                    $mood = 0;
                    $relaxed = 0;
                }

                $mood_level .= $mood . ", ";
                $relaxed_level .= $relaxed . ", ";

                $label_date .= " '" . date("d.M.Y", $start_timestamp) . "', ";

                $start_timestamp = strtotime("+1 day", $start_timestamp);
                $end_time = strtotime("+1 day", $end_time);
            }

            $mood_data = substr($mood_level, 0, -2);
            $relaxed_data = substr($relaxed_level, 0, -2);


            if (!empty($nickname)) {
                $return_array['result'][$id]['nickname'] = $nickname;
                $return_array['result'][$id]['mood_data'] = $mood_data;
                $return_array['result'][$id]['relaxed_data'] = $relaxed_data;
            }
        }

        $label_date = substr($label_date, 0, -2);
        $return_array['label_date'] = $label_date;

        return $return_array;
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
