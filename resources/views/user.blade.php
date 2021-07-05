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
                        The user relationship successfully added!
                    </div>
                </div>
            @endif
                @if ($status==3)
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        The user relationship deleted!
                    </div>
                </div>
            @endif
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('Users') }}</h1>
            </div>
        <!--            <div class="col-lg-2">
                <button type="button" class="btn btn-primary float-left" data-toggle="modal"
                        data-target="#addUser">{{ __('Add user') }}</button>
            </div>-->
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
                                <button type="button" class="btn btn-secondary btn-sm js-tooltip-enabled "
                                        data-toggle="modal" data-placement="bottom"
                                        title="Relationship" data-toggle="tooltip"
                                        data-target="#edit_user_contact_{{$user->id}}"><span
                                        data-feather="plus-circle"></span></button>
                                <button type="button " class="btn btn-secondary btn-sm js-tooltip-enabled "
                                        data-toggle="modal" data-placement="bottom"
                                        title="Edit"
                                        data-target="#edit_user_{{$user->id}}"><span data-feather="edit"></span>
                                </button>
                            </td>
                        </tr>
                        <?php $count++; ?>
                        <div class="modal fade bd-example-modal-lg" id="edit_user_{{$user->id}}"
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
                                            <div class="row">
                                                <label for="relationshipType"
                                                       class="col-sm-2 col-form-label">Nickname:</label>
                                                <div class="col-sm-10 form-group ">
                                                    <input type="text" class="form-control" id="nickname"
                                                           name="nickname"
                                                           value="{{$user->nickname}}">
                                                </div>
                                                <label for="relationshipType"
                                                       class="col-sm-2 col-form-label">Email:</label>
                                                <div class="col-sm-10 form-group ">
                                                    <input type="text" class="form-control" id="email" name="email"
                                                           value="{{$user->email}}">
                                                </div>
                                                <label for="relationshipType"
                                                       class="col-sm-2 col-form-label">Msc:</label>
                                                <div class="col-sm-10 form-group ">
                                                    <input type="text" class="form-control " id="msc" name="msc"
                                                           value="{{$user->msc}}">
                                                </div>
                                                <label for="relationshipType"
                                                       class="col-sm-2 col-form-label">S:</label>
                                                <div class="col-sm-10 form-group ">
                                                    <input type="text" class="form-control" id="s" name="s"
                                                           value="{{$user->s}}">
                                                </div>
                                                <label for="relationshipType"
                                                       class="col-sm-2 col-form-label">P:</label>
                                                <div class="col-sm-10 form-group ">
                                                    <input type="text" class="form-control" id="p" name="p"
                                                           value="{{$user->p}}">
                                                </div>
                                                <label for="relationshipType"
                                                       class="col-sm-2 col-form-label">E:</label>
                                                <div class="col-sm-10 form-group ">
                                                    <input type="text" class="form-control" id="e" name="e"
                                                           value="{{$user->e}}">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="id" name="id"
                                               value="{{$user->id}}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade bd-example-modal-lg" id="edit_user_contact_{{$user->id}}"
                             tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">User Relationship</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ url('saveuserrelationship') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <label for="relationshipType"
                                                           class="col-sm-2 col-form-label">Users:</label>
                                                    <div class="col-sm-4 form-group ">
                                                        <select name="contacted_user_id" id="contacted_user_id"
                                                                class="form-select">
                                                            <?php
                                                            $available_users = App\Http\Controllers\UsersController::getAllNotSelectedUsersById($user->id);

                                                            foreach ($available_users as $oUser) {
                                                                echo '<option value="' . $oUser->id . '">' . $oUser->nickname . ' (' . $oUser->email . ')</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-sm-4 form-group ">
                                                        <select name="relationship_type_id" id="relationship_type_id"
                                                                class="form-select">
                                                            <?php
                                                            $relation_types = App\Http\Controllers\UserrelationshipController::getAllRelationType();

                                                            foreach ($relation_types as $relation_type) {
                                                                echo '<option value="' . $relation_type->id . '">' . $relation_type->type . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2 form-group ">
                                                        <input type="hidden" class="form-control" id="user_id"
                                                               name="user_id" value="{{$user->id}}">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="form-group row">

                                            <ul class="list">
                                                <li>
                                                    <span>ID</span>
                                                    <span>Nickname</span>
                                                    <span>Email</span>
                                                    <span>Type</span>
                                                    <span></span>
                                                </li>
                                                @php
                                                    $count = 1;
                                                     $relationships= App\Http\Controllers\UserrelationshipController ::getAllById($user->id);
                                                @endphp
                                                @foreach($relationships as $relationship)
                                                    <li>
                                                        <span>{{ $relationship->user_id  }}</span>
                                                        <span><?= $relationship->nickname ?></span>
                                                        <span><?= $relationship->email ?></span>
                                                        <span><?= $relationship->type ?></span>
                                                        <span>
                                                    <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                                     <button type="button"
                                                             class="btn btn-secondary btn-sm js-tooltip-enabled "
                                                             data-toggle="modal" data-placement="bottom"
                                                             title="Delete" data-toggle="tooltip"
                                                             onclick="location.href = '/user/deletecontact/id/<?= $relationship->user_relationship_id; ?>'"><span
                                                             data-feather="trash-2"></span></button>
                                                     </div>
                                                         </span>
                                                        <span>
                                                        </span>
                                                    </li>
                                                    <li>
                                                    </li>
                                                    <?php $count++; ?>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                            <label for="relationshipType" class="col-sm-2 col-form-label">Type:</label>
                            <div class="col-sm-10">
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

    <style>
        .list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: table;
            white-space: nowrap;
            width: 100%;
        }

        .list li {
            display: table-row;
        }

        .list li:nth-child(odd) {
            display: table-row;
            font-size: 9pt;
        }

        .list li:nth-child(even) {
            display: table-row;
            font-size: 9pt;
        }

        .list li:nth-child(even):hover {
        }

        .list li:nth-child(1) span:first-child {
            border-top-left-radius: 6px;
        }

        .list li:nth-child(1) span:last-child {
            border-top-right-radius: 6px;
        }


        .list li:nth-child(1) {
            text-transform: uppercase;
            font-size: 8pt;
            font-weight: bold;
        }

        .list li:nth-child(1) span {
            border-bottom: 2px solid;
            padding: 14px;
        }

        .list span {
            text-align: left;
            display: table-cell;
            padding: 6px;
            vertical-align: middle;

        }


    </style>
@endsection
