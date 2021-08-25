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
                <h1 class="h2">{{ __('Users') }}</h1>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-primary float-left" data-toggle="modal"
                        data-target="#addUser">{{ __('Add user') }}</button>
            </div>
            <div class="col-lg-10"></div>
            <div class="table-responsive pt-3">
                <table class="table table-hover table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nickname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Msc</th>
                        <th scope="col">S</th>
                        <th scope="col">P</th>
                        <th scope="col">E</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; ?>
                    @foreach($users_result as $user)
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{$user->nickname}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->msc}}</td>
                            <td>{{$user->s}}</td>
                            <td>{{$user->p}}</td>
                            <td>{{$user->e}}</td>
                            <td class="text-right">
                                <button type="button" class="btn btn-secondary btn-sm " data-toggle="modal"
                                        data-target="#edit_user_{{$user->id}}"><span data-feather="edit"></span></button>
                                <!--                                <button type="button" class="btn btn-secondary btn-sm " data-toggle="modal"
                                                                        data-target="#addRelationship"><span data-feather="trash-2"></span></button>-->
                            </td>
                        </tr>
                        <?php $count++; ?>
                        <div class="modal fade  show bd-example-modal-lg" id="edit_user_{{$user->id}}"
                             tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ url('edituser') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="relationshipType"
                                                       class="col-lg-2 col-form-label">Type:</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" id="type" name="type"
                                                           value="{{$user->nickname}}">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{$user->id}}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade  show bd-example-modal-lg" id="edit_user_{{$user->id}}"
                             tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ url('edituser') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group row">
                                                <label for="relationshipType"
                                                       class="col-lg-2 col-form-label">Type:</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control" id="type" name="type"
                                                           value="{{$user->nickname}}">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{$user->id}}">
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
