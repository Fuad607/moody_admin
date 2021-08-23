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
                <h1 class="h2">{{ __('Admin') }}</h1>
            </div>
                <form method="POST" action="{{ url('editadmin') }}" enctype="multipart/form-data"  id="editadmin">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <label for="name"
                                   class="col-lg-2 col-form-label">Name:</label>
                            <div class="col-lg-10 form-group ">
                                <input type="text" class="form-control"
                                       name="name"
                                       value="{{$admin_result->name}}">
                            </div>
                            <label for="name"
                                   class="col-lg-2 col-form-label">Password:</label>
                            <div class="col-lg-10 form-group ">
                                <input type="password" class="form-control password" name="password" id="password"
                                       value="">
                            </div>
                            <label for="name"
                                   class="col-lg-2 col-form-label">Confirm Password:</label>
                            <div class="col-lg-10 form-group ">
                                <input type="password" class="form-control" name="confirm_password"
                                       value="">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="id" value="{{$admin_result->id}}">
                    <button type="submit" class="btn btn-primary footer-form-button-edit">Submit</button>
                    <button type="button" class="btn btn-secondary" onclick="location.href = '/admin'">Back</button>
                </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
            integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        jQuery("#editadmin").validate({
            errorClass: "invalid-feedback animated fadeIn",
            errorElement: "div",
            errorPlacement: function (e, r) {
                jQuery(r).addClass("is-invalid"), jQuery(r).parents(".form-group").append(e);
            },
            highlight: function (e) {
                jQuery(e).parents(".form-group").find(".is-invalid").removeClass("is-invalid").addClass("is-invalid");
            },
            unhighlight: function (e) {
                jQuery(e).parents(".form-group").find(".is-invalid").removeClass("is-invalid");
            },
            success: function (e) {
                jQuery(e).parents(".form-group").find(".is-invalid").removeClass("is-invalid"), jQuery(e).remove();
            },
            showErrors: function (errorMap, errorList) {
                if (this.numberOfInvalids() == 0) {
                    $(".footer-form-button-edit").attr("disabled", false);
                } else {
                    $(".footer-form-button-edit").attr("disabled", true);
                }
                this.defaultShowErrors();
            },
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                }

            }
        });
    </script>
@endsection


