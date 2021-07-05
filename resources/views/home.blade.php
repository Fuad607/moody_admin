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
            @if (!empty($status))
                <div class="col-lg-12">
                    <div class="alert alert-success" role="alert">
                        {{ $status }}
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
