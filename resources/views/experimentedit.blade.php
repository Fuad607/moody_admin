@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if(!empty($error))
                <div class="col-lg-12">
                    <div class="alert alert-danger">
                        {{  $error }}
                    </div>
                </div>
            @endif
                @if ($status==1)
                    <div class="col-lg-12">
                        <div class="alert alert-success" role="alert">
                            The user data successfully changed!
                        </div>
                    </div>
                @endif
                @if ($status==2)
                    <div class="col-lg-12">
                        <div class="alert alert-success" role="alert">
                            The user successfully added!
                        </div>
                    </div>
                @endif
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Experiment</h1>
            </div>
            <form  action="{{ url('setexperiement') }}" method="POST" enctype="multipart/form-data" name="formExperiment">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <label for="relationshipType"
                               class="col-2 col-form-label">Name:</label>
                        <div class="col-lg-10 form-group ">
                            <input type="text" class="form-control" id="name"
                                   name="name"
                                   value="{{ $experiment_result->name }}">
                        </div>
                        <label for="relationshipType"
                               class="col-lg-2 col-form-label">Notifications:</label>
                        <div class="col-lg-10 form-group ">
                            <select name="notifications" id="notifications"
                                    class="form-select">
                                <?php
                                $array = [1 => "synchronously", 2 => "asynchronously"];

                                foreach ($array as $id => $value) {
                                    if ($experiment_result->notifications == $id) {
                                        echo '<option value="' . $id . '" selected>' . $value . '</option>';
                                    } else {
                                        echo '<option value="' . $id . '">' . $value . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <label for="relationshipType"
                               class="col-lg-2 col-form-label">Frequency:</label>
                        <div class="col-lg-4 form-group ">
                            <input type="number" class="form-control" id="frequency"
                                   name="frequency"
                                   value="{{ $experiment_result->frequency }}">
                        </div>
                        <label for="relationshipType"
                               class="col-lg-2 col-form-label">Range:</label>
                        <div class="col-lg-4 form-group ">
                            <select name="range" id="range"
                                    class="form-select">
                                <?php
                                $array_range = [1 => "Morning", 2 => "Afternoon"];

                                foreach ($array_range as $id => $value) {
                                    if ($experiment_result->range == $id) {
                                        echo '<option value="' . $id . '" selected>' . $value . '</option>';
                                    } else {
                                        echo '<option value="' . $id . '">' . $value . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <label for="relationshipType"
                               class="col-lg-2 col-form-label">Start Date:</label>
                        <div class="col-lg-4 form-group ">
                            <input type="text" class="form-control start_timestamp" id="start_timestamp"
                                   name="start_timestamp"
                                   value="{{ $experiment_result->start_timestamp>0? date("m/d/Y",$experiment_result->start_timestamp): date("m/d/Y") }}">
                        </div>
                        <label for="relationshipType"
                               class="col-lg-2 col-form-label">End Date:</label>
                        <div class="col-lg-4 form-group ">
                            <input type="text" class="form-control end_timestamp" id="end_timestamp"
                                   name="end_timestamp"
                                   value="{{ $experiment_result->end_timestamp>0? date("m/d/Y",$experiment_result->end_timestamp): date("m/d/Y")  }}">
                        </div>
                        <label for="relationshipType"
                               class="col-lg-2 col-form-label">User:</label>
                        <div class="col-lg-10 form-group ">
                            <?php
                            $selected_users = explode(",", $experiment_result->user_ids);
                            $users = App\Http\Controllers\UsersController::index();
                            foreach ($users as $user) {

                            if (in_array($user->id, $selected_users)) {
                                $checked = "checked";
                            } else {
                                $checked = "";
                            }
                            ?>
                            <div class="col-lg-2 form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="{{ $user->id }}"
                                       value="{{ $user->id }}" name="users[{{ $user->id }}]"   <?= $checked; ?>>
                                <label class="form-check-label" for="{{ $user->id }}">{{ $user->nickname }}</label>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control" id="id" name="id" value="{{ $experiment_result->id }}">
                <div class=" float-left">
                    <button type="submit" class="btn btn-primary" name="submitExperiment">Submit</button>
                </div>
            </form>
        </div>
        <br>
        <h1 class="h2">Experiment Result</h1>
        <a  class="btn btn-secondary"  href="{{ url('/downloadexperiment?id='.$experiment_result->id ) }}">Download Csv</a>
        <br>
        <br>
       <div class="row">
           <div class="col-lg-1"> <p style="border-style: solid;" class="text-center"> Mood</div>
           <div class="col-lg-1"> <p style="border-style: dashed;" class="text-center"> Relax</div>
       </div>
        <br>
        <canvas class="my-4 w-100" id="myChart" width="900" height="380" ></canvas>

        <br>
        <h1 class="h2">Result Per User</h1>
        <br>

        <?php
//        $mood_data = substr($mood_level, 0, -2);
//        $relaxed_data = substr($relaxed_level, 0, -2);
//        $label_date = substr($label_date, 0, -2);

        foreach ($experiment_survey_result_by_users as $experiment_survey_result_by_user){
        ?>

        <div class="col-lg-1"> <p class="text-center">   <?=  $experiment_survey_result_by_user['nickname'] ?></div>
        <canvas class="my-4 w-100" id="myChart_<?= $experiment_survey_result_by_user['id']; ?>" height="300" ></canvas>
        <br>
<script>
    var ctx_<?= $experiment_survey_result_by_user['id']; ?> = document.getElementById('myChart_<?= $experiment_survey_result_by_user['id']; ?>')

    const labels_<?= $experiment_survey_result_by_user['id']; ?> = [<?= $experiment_survey_result_by_user['label_date']; ?>];

    var colorGenerate = function () {
        return '#' + (Math.random().toString(16) + '0000000').slice(2, 8);
    };

    var myChart = new Chart(ctx_<?= $experiment_survey_result_by_user['id']; ?>, {
        type: 'line',
        data: {
            labels: [<?= $experiment_survey_result_by_user['label_date']; ?>],
            datasets: [
                {
                    label: 'Mood',
                    fill: false,
                     borderColor:  colorGenerate(),
                    data: [<?= $experiment_survey_result_by_user['mood_data'] ?>],
                },
                {
                    label: 'Relax',
                    fill: false,
                     borderColor: colorGenerate(),
                    borderDash: [5, 5],
                    data: [<?= $experiment_survey_result_by_user['relaxed_data'] ?>],
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Line Chart'
                },
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Value'
                    }
                }
            }
        },
    })
</script>
        <?php
        } ?>
    </div>

    <script>
        $('.start_timestamp').datepicker({
            uiLibrary: 'bootstrap4'
        });
        $('.end_timestamp').datepicker({
            uiLibrary: 'bootstrap4'
        });

        function updateTextInput(val) {
            document.getElementById('textInput').innerHTML=val;
        }

        var ctx = document.getElementById('myChart')

        var randomColorGenerator = function () {
            return '#' + (Math.random().toString(16) + '0000000').slice(2, 8);
        };

        const labels = [<?= $label_date; ?>];

        const data = {
            labels: labels,
            datasets: [
                    <?php
                    foreach ($experiment_survey_results as $experiment_survey_result)
                    {
                        ?>
                {
                    label: '<?=  $experiment_survey_result['nickname'] ?>',
                    fill: false,
                  //  backgroundColor: randomColorGenerator(),
                    borderColor: randomColorGenerator(),
                    data: [<?= $experiment_survey_result['mood_data'] ?>],
                },
                {
                    label: '<?=  $experiment_survey_result['nickname'] ?>',
                    fill: false,
                   // backgroundColor: randomColorGenerator(),
                    borderColor: randomColorGenerator(),
                    borderDash: [5, 5],
                    data: [<?= $experiment_survey_result['relaxed_data'] ?>],
                },
                <?php
                } ?>
            ]
        };

        var myChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart'
                    },
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                }
            },
        })
    </script>
@endsection

