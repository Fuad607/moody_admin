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
                        The experiment data successfully changed!
                    </div>
                </div>
            @endif
            @if ($status==2)
                <div class="col-lg-12">
                    <div class="alert alert-success" role="alert">
                        The experiment relationship successfully added!
                    </div>
                </div>
            @endif
            @if ($status==3)
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        The experiment relationship deleted!
                    </div>
                </div>
            @endif
                @if ($status==4)
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        The experiment successfully deleted!
                    </div>
                </div>
            @endif
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('Experiments') }}</h1>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-primary float-left"
                        onclick="location.href = '/experiment/createexperiment'">{{ __('Create Experiment') }}</button>
            </div>
            <div class="col-lg-10"></div>
            <div class="table-responsive pt-3">
                <table class="table table-hover table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col-lg">#</th>
                        <th scope="col-lg">Name</th>
                        <th scope="col-lg" class="text-nowrap">Start <span>
                                <a class="js-tooltip-enabled" data-toggle="tooltip" href="/experiment?column_name=start_timestamp&sort=ASC" title="Sort ascending"> <i  data-feather="arrow-up"> </i></a>
                               <a class="js-tooltip-enabled" data-toggle="tooltip" href="/experiment?column_name=start_timestamp&sort=DESC" title="Sort descending"><i data-feather="arrow-down"></i></a></span>
                        </th>
                        <th scope="col-lg" class="text-nowrap">End <span>
                                <a class="js-tooltip-enabled" data-toggle="tooltip" href="/experiment?column_name=end_timestamp&sort=ASC" title="Sort ascending"> <i  data-feather="arrow-up"> </i></a>
                               <a class="js-tooltip-enabled" data-toggle="tooltip" href="/experiment?column_name=end_timestamp&sort=DESC" title="Sort descending"><i data-feather="arrow-down"></i></a></span></th>
                        <th scope="col-lg"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1;
                    foreach($experiment_results as $experiment_result){
                    if (empty($experiment_result->name))
                        continue;
                    ?>
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{$experiment_result->name}}</td>
                        <td>{{ $experiment_result->start_timestamp>0? date("m/d/Y",$experiment_result->start_timestamp): 0}}</td>
                        <td>{{ $experiment_result->end_timestamp>0? date("m/d/Y",$experiment_result->end_timestamp): 0 }}</td>
                        <td class="text-right">
                            <button type="button" class="btn btn-secondary btn-sm js-tooltip-enabled "
                                    data-placement="bottom" title="Edit" data-toggle="tooltip"
                                    onclick="location.href = '/experimentedit?edit_id={{$experiment_result->id}}'"><span
                                    data-feather="edit"></span></button>
                            <button type="button " class="btn btn-secondary btn-sm js-tooltip-enabled "
                                    data-toggle="modal" data-placement="bottom"
                                    title="Delete"
                                    data-target="#delete_experiment_{{$experiment_result->id}}"><span data-feather="trash-2"></span>
                            </button>
                        </td>
                    </tr>
                    <div class="modal fade bd-example-modal-lg" id="delete_experiment_{{$experiment_result->id}}"
                         tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Experiment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ url('deleteexperiment') }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <label for="name"
                                                   class="col-lg-12 col-form-label">Are you sure to delete the experiment?</label>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="id" name="id"
                                           value="{{$experiment_result->id}}">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php $count++;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="addRelationship" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relationship</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ url('addrelationship') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="relationshipType" class="col-lg-2 col-form-label">Type:</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="type" name="type" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
