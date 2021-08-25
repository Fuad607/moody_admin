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
                        The relationship type successfully changed!
                    </div>
                </div>
            @endif
                @if ($status==2)
                <div class="col-lg-12">
                    <div class="alert alert-success" role="alert">
                        The relationship type successfully added!
                    </div>
                </div>
            @endif
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('Relationship Types') }}</h1>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-primary float-left" data-toggle="modal"
                        data-target="#addRelationship">{{ __('Add Relationship Type') }}</button>
            </div>
            <div class="col-lg-10"></div>
            <div class="table-responsive pt-3">
                <table class="table table-hover table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Type</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; ?>
                    @foreach($result_type as $relation_type)
                        @if(empty($relation_type->type))
                            @continue
                        @endif
                        <tr>
                            <td> {{ $count }}</td>
                            <td>{{$relation_type->type}}</td>
                            <td class="text-right">
                                <button type="button" class="btn btn-secondary btn-sm " data-toggle="modal"
                                        data-target="#edit_relationship_{{$relation_type->id}}"><span data-feather="edit"></span></button>
<!--                                <button type="button" class="btn btn-secondary btn-sm " data-toggle="modal"
                                        data-target="#addRelationship"><span data-feather="trash-2"></span></button>-->
                            </td>
                        </tr>
                        <?php $count++; ?>
                        <div class="modal fade  show bd-example-modal-lg" id="edit_relationship_{{$relation_type->id}}"
                             tabindex="-1" role="dialog"
                             aria-labelledby="modal-block-normal" data-backdrop="static" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Relationship</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ url('editrelationshiptype') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="relationshipType"
                                                       class="col-lg-2 col-form-label">Type:</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" id="type" name="type"
                                                           value="{{$relation_type->type}}">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{$relation_type->id}}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="modal fade  show bd-example-modal-lg" id="addRelationship" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Relationship</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ url('addrelationshiptype') }}">
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
