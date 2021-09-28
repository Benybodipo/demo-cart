@extends('layouts.main')
@section('title', 'Request API Key')
@section('main')
    <div class="row">
        <div class="pt-4 pb-4">
            @include('includes.flash-message')
            <form action="" method="POST" id="request-key-form">
                <h3 class="text-center mb-4">Access Cart</h3>
                <br>
                <div class="form-group">
                    <input type="text" class="form-control" name="key" value="{{old('key')}}" placeholder="Enter you API Access key">
                </div>
                <button class="btn btn-success btn-block">
                    Access Cart
                </button>
                @csrf
            </form>
        </div>
    </div>
@endsection