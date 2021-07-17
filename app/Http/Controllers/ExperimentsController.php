<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Experiments;
use App\Http\Resources\Experiments as ExperimentResource;

class ExperimentsController extends Controller
{
    public static function index($admin_id)
    {
        $where_condition = 'WHERE ';

        $experiments = DB::select('SELECT experiments.* FROM `experiments` WHERE name!=""  AND admin_id=' . $admin_id);

        return $experiments;
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
        foreach ($user_ids as $id) {
            //
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
                               survey.user_id , users.nickname FROM `survey`
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

                $label_date .=" '".date("d.M.Y",$start_timestamp). "', ";

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
        $return_array['label_date']=$label_date;

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

}
