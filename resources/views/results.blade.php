@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2">
                <div class="card mb-3 mt-5 shadow">
                    @if($type === 'image/png' || $type === 'image/jpg' || $type === 'image/gif' || $type === 'image/jpeg')
                        <img src="data:{{$type}};base64, {{$image}}" class="card-img-top" alt="image">
                    @else
                        <div class="card-header">
                            <h3 class="h3" style="text-align: center">Data file</h3>
                        </div>
                    @endif
                    <div class="card-body mt-2 text-center">
                        <h4>{{$filename}}</h4>
                        <p>{{$type}}</p>
                        <br>
                        @if($result)
                            <div class="alert alert-success" role="alert">
                                No Personally identifiable information found.
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                Personally identifiable information found!!
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white" align="center">
                        <a href="{{route('home')}}" class="btn btn-info">Go Back</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection