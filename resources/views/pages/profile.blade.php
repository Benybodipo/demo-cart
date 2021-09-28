@extends('layouts.main')
@section('title', 'Products')
@section('main')
    <div class="row">
        <div class="col-sm-12 pt-4 pb-4">
            <form action="" method="POST" id="request-key-form">
                <h3 class="text-center mb-4">My Cart profile</h3>
                <br>
                <div class="form-group">
                    <strong>API KEY:</strong>
                    <span>{{getenv('DEMO_API_KEY')}}</span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="user" value="{{getenv('DEMO_API_USER')}}">
                </div>
                <button class="btn btn-success btn-block">
                    Update
                </button>
                <br>
                <br>
                <br>
                <a class="btn btn-danger" style="color: white;">
                    Delete Cart
                </a>
                @csrf
            </form>
        </div>
    </div>
@endsection