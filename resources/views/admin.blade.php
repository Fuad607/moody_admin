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
                        The admin data successfully changed!
                    </div>
                </div>
            @endif
            @if ($status==2)
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        The admin email already exist!
                    </div>
                </div>
            @endif
            @if ($status==3)
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        The admin successfully deleted!
                    </div>
                </div>
            @endif
            @if ($status==4)
                <div class="col-lg-12">
                    <div class="alert alert-success" role="alert">
                        The admin successfully added!
                    </div>
                </div>
            @endif
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ __('Admins') }}</h1>
            </div>
            <div class="col-lg-12">
                <form method="POST" action="{{ url('addadmin') }}">
                    @csrf

                    <div class="row">
                        <label for="name"
                               class="col-lg-2 col-form-label">Name:</label>
                        <div class="col-lg-4 form-group ">
                            <input id="name" type="text"
                                   class="form-control @error('name') is-invalid @enderror" name="name"
                                   value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <label for="email"
                               class="col-lg-2 col-form-label">Email:</label>
                        <div class="col-lg-4 form-group ">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <label for="password"
                               class="col-lg-2 col-form-label">Password:</label>
                        <div class="col-lg-4 form-group ">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   required autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <label for="password"
                               class="col-lg-2 col-form-label">Confirm Password:</label>
                        <div class="col-lg-4 form-group ">
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required
                                   autocomplete="new-password">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Admin</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-12 border-bottom"></div>
            <div class="table-responsive pt-3">
                <table class="table table-hover table-vcenter">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 1; ?>
                    @foreach($admin_results as $admin)
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{$admin->name}}</td>
                            <td>{{$admin->email}}</td>
                            <td class="text-right">
                                <button type="button " class="btn btn-secondary btn-sm js-tooltip-enabled "
                                        data-toggle="modal" data-placement="bottom"
                                        title="Edit"
                                        data-target="#edit_admin_{{$admin->id}}"><span data-feather="edit"></span>
                                </button>
                                <button type="button " class="btn btn-secondary btn-sm js-tooltip-enabled "
                                        data-toggle="modal" data-placement="bottom"
                                        title="Delete"
                                        data-target="#delete_admin_{{$admin->id}}"><span data-feather="trash-2"></span>
                                </button>
                            </td>
                        </tr>
                        <?php $count++; ?>
                        <div class="modal fade bd-example-modal-lg" id="edit_admin_{{$admin->id}}"
                             tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Admin</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ url('editadmin') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <label for="name"
                                                       class="col-lg-2 col-form-label">Name:</label>
                                                <div class="col-lg-10 form-group ">
                                                    <input type="text" class="form-control" id="name"
                                                           name="name"
                                                           value="{{$admin->name}}">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="id" name="id"
                                               value="{{$admin->id}}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade bd-example-modal-lg" id="delete_admin_{{$admin->id}}"
                             tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Admin</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ url('deleteadmin') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <label for="name"
                                                       class="col-lg-12 col-form-label">Are you sure to delete the admin?</label>
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="id" name="id"
                                               value="{{$admin->id}}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Delete</button>
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

    <div class="modal fade bd-example-modal-lg" id="addAdmin" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
