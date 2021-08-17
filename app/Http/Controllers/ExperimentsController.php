<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Experiments;
use App\Http\Resources\Experiments as ExperimentResource;

class ExperimentsController extends Controller
{
    public static function index($admin_id, $column_name = "", $sort = "")
    {
        $sort_condition = "";
        if (!empty($column_name)) {
            $sort_condition = ' ORDER BY ' . $column_name . " " . $sort;
        }

        $experiments = DB::select("SELECT experiments.* FROM experiments WHERE name !=''  AND admin_id=" . $admin_id . $sort_condition);

        return $experiments;
    }

    public static function getCurrentExperiment(Request $request)
    {
        $experiments = DB::select("SELECT experiments.* FROM experiments WHERE  start_timestamp <=" . time() . " AND end_timestamp >=" . time());

        $current=new \stdClass();
        $future=new \stdClass();
        if (!empty($experiments)) {
            $user_ids = $experiments[0]->user_ids;
            $user_ids = explode(", ", $user_ids);

            foreach ((array)$user_ids as $id) {
                if ($id == $request->user_id) {
                    $current = $experiments[0];
                }
            }
        } else {
            $future_experiments = DB::select("SELECT experiments.* FROM experiments WHERE  start_timestamp >" . time() . " Order by start_timestamp ASC");

            if (!empty($future_experiments)) {
                $user_ids = $future_experiments[0]->user_ids;
                $user_ids = explode(", ", $user_ids);

                foreach ((array)$user_ids as $id) {
                    if ($id == $request->user_id) {
                        $future = $future_experiments[0];
                    }
                }
            }
        }

        return response()->json(['current' => $current, 'future' => $future]);
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
        $experiment = new Experiments;
        $experiment->admin_id = $request->input('admin_id');

        if ($experiment->save()) {
            $response = 'success';
            $id = $experiment->id;
            $body = ['id' => $id];
        } else {
            $response = 'database_error';
        }

        return response()->json(['response' => $response, 'body' => $body]);
    }

    public static function getAllById($id)
    {
        $experiments = DB::select('SELECT * FROM experiments WHERE experiments.id=' . $id);
        $user_ids = $experiments[0]->user_ids;
        $user_ids = explode(", ", $user_ids);

        $return_array = [];

        $label_date = "";

        if (!empty($experiments[0]->user_ids)) {
            foreach ((array)$user_ids as $id) {

                $users = DB::select('SELECT * FROM users WHERE users.id=' . $id);
                //$start_timestamp= mktime(0, 0, 0, date("m",$experiments[0]->start_timestamp), date("d",$experiments[0]->start_timestamp), date("Y",$experiments[0]->start_timestamp));
                $start_timestamp = $experiments[0]->start_timestamp;
                $end_timestamp = $experiments[0]->end_timestamp;
                $end_time = mktime(23, 59, 59, date("m", $experiments[0]->start_timestamp), date("d", $experiments[0]->start_timestamp), date("Y", $experiments[0]->start_timestamp));

                $mood_level = "";
                $relaxed_level = "";
                $nickname = "";

                while ($start_timestamp <= $end_timestamp) {

                    $experiment_result = DB::select(
                        'SELECT avg(survey.mood_level-5) as mood_level, avg(survey.relaxed_level-5) as relaxed_level,
                               survey.user_id , users.nickname FROM survey
                               LEFT JOIN users on users.id=survey.user_id
                   WHERE users.id =' . $id . '  AND survey.timestamp >=' . $start_timestamp . '  AND survey.timestamp<=' . $end_time . ' Group by user_id ,nickname'
                    );

                    /*   $experiment_result = DB::table('survey')
                           ->select(DB::raw('avg(survey.mood_level-5) as mood_level, avg(survey.relaxed_level-5) as relaxed_level,
              survey.user_id '))
                           ->leftjoin('users','users.id','=','survey.user_id')
                           ->whereRaw('users.id =' . $id . '  AND survey.timestamp >=' . $start_timestamp . '  AND survey.timestamp<=' . $end_time)
                           ->groupBy('user_id')
                           ->get();*/

                    $nickname = $users[0]->nickname;
                    if (!empty($experiment_result)) {
                        $mood = round($experiment_result[0]->mood_level);
                        $relaxed = round($experiment_result[0]->relaxed_level);

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
        } else {
            $return_array['result'] = [];
        }
        $label_date = substr($label_date, 0, -2);
        $return_array['label_date'] = $label_date;

        return $return_array;
    }

    public static function getAllByUsers($id)
    {
        $experiments = DB::select('SELECT * FROM experiments WHERE experiments.id=' . $id);
        $user_ids = $experiments[0]->user_ids;
        $user_ids = explode(", ", $user_ids);

        $return_array = [];

        if (!empty($experiments[0]->user_ids)) {
            foreach ($user_ids as $id) {
                //
                $nickname = "";
                $mood_level = "";
                $relaxed_level = "";
                $label = "";
                $user_contacted = "";
                $user_special_situation = "";

                $experiment_results = DB::select(
                    'SELECT mood_level-5 as mood_level, survey.relaxed_level-5 as relaxed_level,survey.timestamp,
                            survey.user_id , users.nickname,survey.id as survey_id
                               FROM survey
                               LEFT JOIN users on users.id=survey.user_id
                   WHERE users.id =' . $id
                );

                foreach ($experiment_results as $experiment_result) {
                    $nickname = $experiment_result->nickname;

                    $contacted_user = "";
                    $mood_level .= $experiment_result->mood_level . ", ";
                    $relaxed_level .= $experiment_result->relaxed_level . ", ";
                    $label .= " '" . date("d.M.Y", $experiment_result->timestamp) . "', ";

                    $usermeeting = new UsermeetingController();
                    $userspecialsituation = new UserspecialsituationController();

                    $user_meeting_results = $usermeeting->index($experiment_result->survey_id);
                    $user_special_situation_results = $userspecialsituation->index($experiment_result->survey_id);
                    $meeting_type = "";

                    foreach ($user_meeting_results as $user_meeting_result) {

                        if ($user_meeting_result->contacted_user_id > 0) {
                            $user = self::getUser($user_meeting_result->contacted_user_id);

                            switch ($user_meeting_result->meeting_type) {
                                case 1:
                                    $meeting_type = "F2F";
                                    break;
                                case 2:
                                    $meeting_type = "Video";
                                    break;
                                case 3:
                                    $meeting_type = "Other";
                                    break;

                            }

                            $contacted_user .= $user[0]->nickname . " " . $meeting_type . ", ";
                        }
                    }
                    $contacted_user = substr($contacted_user, 0, -2);

                    $user_contacted .= "'" . $contacted_user . "', ";

                    $special_situation_type = "";
                    $special_situation = "";
                    foreach ($user_special_situation_results as $user_special_situation_result) {
                        if (!empty($user_special_situation_result->special_situation)) {
                            if ($user_special_situation_result->special_situation_type == 0) {
                                $special_situation_type = "Negative";
                            } elseif ($user_special_situation_result->special_situation_type == 1) {
                                $special_situation_type = "Positive";
                            }

                            $special_situation .= $user_special_situation_result->special_situation . " " . $special_situation_type . ", ";
                        }
                    }
                    $special_situation = substr($special_situation, 0, -2);

                    $user_special_situation .= "'" . $special_situation . "', ";

                }
                if (!empty($nickname)) {
                    $mood_data = substr($mood_level, 0, -2);
                    $relaxed_data = substr($relaxed_level, 0, -2);
                    $label_date = substr($label, 0, -2);
                    $user_contacted = substr($user_contacted, 0, -2);
                    $user_special_situation = substr($user_special_situation, 0, -2);

                    $return_array[$id]['id'] = $id;
                    $return_array[$id]['nickname'] = $nickname;
                    $return_array[$id]['label_date'] = $label_date;
                    $return_array[$id]['mood_data'] = $mood_data;
                    $return_array[$id]['relaxed_data'] = $relaxed_data;
                    $return_array[$id]['user_contacted'] = $user_contacted;
                    $return_array[$id]['user_special_situation'] = $user_special_situation;
                }
            }
        }

        return $return_array;
    }

    public function setData(Request $request)
    {
        $experiment = Experiments::findOrFail($request->id);

        $start_date = explode("/", $request->input('start_timestamp'));
        $start = mktime(0, 0, 0, $start_date[0], $start_date[1], $start_date[2]);

        $end_date = explode("/", $request->input('end_timestamp'));
        $end = mktime(23, 59, 59, $end_date[0], $end_date[1], $end_date[2]);

        $experiment->name = $request->input('name');
        $experiment->frequency = $request->input('frequency');
        $experiment->range = $request->input('range');
        $experiment->notifications = $request->input('notifications');
        $experiment->start_timestamp = $start;
        $experiment->end_timestamp = $end;

        if ($experiment->save()) {
            return new ExperimentResource($experiment);
        }
    }

    public function setUsers($id, $users)
    {
        $experiment = Experiments::findOrFail($id);

        $experiment->user_ids = $users;

        if ($experiment->save()) {
            return new ExperimentResource($experiment);
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
        $experiment = Experiments::findOrFail($id);

        return new ExperimentResource();
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
        $experiment = Experiments::findOrFail($id);
        if ($experiment->delete()) {
            return new ExperimentResource($experiment);
        }
    }


    public static function getAllForCsv($id)
    {
        $experiments = DB::select('SELECT * FROM experiments WHERE experiments.id=' . $id);
        $user_ids = $experiments[0]->user_ids;
        $user_ids = explode(", ", $user_ids);
        $return_array = [];
        $return_array[] = array('Nickname', 'Mood level', 'Relaxation level', 'Date created', 'Contacted user', 'Contacted type', 'Special situation', 'Type');
        $survey_result = DB::select(
            "SELECT survey.mood_level-5 as mood_level, survey.relaxed_level-5 as relaxed_level,survey.timestamp,
                               survey.user_id , users.nickname,user_meeting.meeting_type,user_meeting.contacted_user_id,user_special_situation.special_situation,
                               user_special_situation.special_situation_type
                               FROM survey
                               LEFT JOIN users on users.id=survey.user_id
                               LEFT JOIN user_meeting on user_meeting.survey_id=survey.id
                               LEFT JOIN user_special_situation on user_special_situation.survey_id=survey.id
                  WHERE  survey.timestamp >=" . $experiments[0]->start_timestamp . "  AND survey.timestamp<=" . $experiments[0]->end_timestamp . "  ORDER by survey.user_id,survey.timestamp"
        );

        foreach ($survey_result as $result) {
            if (in_array($result->user_id, $user_ids)) {
                $contacted_user_id = "";
                $meeting_type = "";
                $special_situation_type = "";
                if ($result->contacted_user_id > 0) {
                    $user = self::getUser($result->contacted_user_id);
                    $contacted_user_id = $user[0]->nickname;
                }
                $date_time = date("d.M.Y", $result->timestamp);
                switch ($result->meeting_type) {
                    case 1:
                        $meeting_type = "F2F";
                        break;
                    case 2:
                        $meeting_type = "Video";
                        break;
                    case 3:
                        $meeting_type = "Other";
                        break;

                };
                if (!empty($result->special_situation)) {
                    if ($result->special_situation_type == 0) {
                        $special_situation_type = "Negative";
                    } elseif ($result->special_situation_type == 1) {
                        $special_situation_type = "Positive";
                    }
                }

                $return_array[] = array($result->nickname, $result->mood_level, $result->relaxed_level, $date_time, $contacted_user_id, $meeting_type, $result->special_situation, $special_situation_type);

            }
        }

        return $return_array;
    }

    public static function getUser($id)
    {
        $user = DB::select("SELECT users.* FROM users WHERE users.id=" . $id);

        return $user;
    }
}
