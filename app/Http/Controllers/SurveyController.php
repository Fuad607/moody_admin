<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use  App\Http\Requests;
use App\Survey;
use App\Http\Resources\Survey as SurveyResource;
use Illuminate\Support\Facades\DB;
use function Couchbase\passthruEncoder;

class   SurveyController extends Controller
{
    public function index($user_id,$start,$end)
    {
        if($start!=0 && $start!="" && $end!=0 && $end!=""){
            $time_condition=" AND timestamp>=".$start." AND timestamp<=".$end;
        }

        $survey = DB::select(
            'SELECT survey.* FROM survey`
                WHERE survey.deleted=0 AND survey.user_id='.$user_id.$time_condition
        );

        return $survey;
    }

    public function getAll(Request $request)
    {
        $survey = DB::select(
            'SELECT survey.* FROM `survey`
                WHERE survey.deleted=0 AND survey.user_id='.$request->user_id.'
                Order by timestamp '
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

    public static function getAllById(Request  $request)
    {
        $user_id=$request->user_id;
        $contacted_user_ids=$request->contacted_user_ids;
        $start=$request->start;
        $end=$request->end;

        $user_relationship = DB::select('SELECT  user_relationship.contacted_user_id as user_id FROM user_relationship
     WHERE user_relationship.deleted=0 AND user_relationship.user_id=' . $user_id);
        $user_relationship = json_decode(json_encode($user_relationship), true);
        $users_array[] = array('user_id'=>(int)$user_id);
        $users_array=array_merge($user_relationship,$users_array);

        $return_array = [];
        $return_data = [];

        $label_date = [];
        $count=1;

        foreach ($users_array as $result) {
            //
            $users = DB::select('SELECT * FROM users WHERE users.id=' . $result['user_id']);

            $start_date = explode(".", $start);
            $start_timestamp= mktime(0, 0, 0,$start_date[1],$start_date[0], $start_date[2]);
            $end_time = mktime(23, 59, 59, $start_date[1], $start_date[0],  $start_date[2]);

            $end_date = explode(".", $end);
            $end_timestamp = mktime(0, 0, 0, $end_date[1], $end_date[0], $end_date[2]);

            $mood_level =[];
            $relaxed_level =[];

            if (!empty($users)) {
                $nickname = $users[0]->nickname;
            while ($start_timestamp <= $end_timestamp) {

                $survey_result = DB::select(
                    'SELECT avg(survey.mood_level-5) as mood_level, avg(survey.relaxed_level-5) as relaxed_level,
                               survey.user_id , users.nickname FROM survey
                               LEFT JOIN users on users.id=survey.user_id
                   WHERE users.id =' . $result['user_id'] . '  AND survey.timestamp >=' . $start_timestamp . '  AND survey.timestamp<=' . $end_time . ' Group by user_id ,nickname'
                );


                if (!empty($survey_result)) {
                    $mood = round($survey_result[0]->mood_level);
                    $relaxed = round($survey_result[0]->relaxed_level);

                } else {
                    $mood = 0;
                    $relaxed = 0;
                }

                $mood_level []= $mood ;
                $relaxed_level []= $relaxed ;

                if ($count==1){
                    $label_date []=   date("d.M.Y", $start_timestamp);
                }


                $start_timestamp = strtotime("+1 day", $start_timestamp);
                $end_time = strtotime("+1 day", $end_time);
            }

           // $mood_data = substr($mood_level, 0, -2);
           // $relaxed_data = substr($relaxed_level, 0, -2);

                $return_data[]=array(
                    'id'=>$result['user_id'],
                    'nickname'=>$nickname,
                    'mood_data'=>$mood_level,
                    'relaxed_data'=>$relaxed_level
                );
            }
            $count++;
        }
        $return_array['result']=$return_data;
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
