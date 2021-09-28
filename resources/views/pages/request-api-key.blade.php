@extends('layouts.main')
@section('title', 'Request API Key')
@section('main')
    <div class="row">
        <div class="pt-4 pb-4">
            @include('includes.flash-message')
            <form action="{{route('request-api-key')}}" method="POST" id="request-key-form">
                <h3 class="text-center mb-4">Request API Key</h3>
                <br>
                <div class="form-group">
                    <label for="name" class=" ">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                </div>
                <div class="form-group">
                    <label for="email" class=" ">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}">
                </div>
                <button class="btn btn-success btn-block">
                    <i class="fas fa-key"></i>
                    Request API Key
                </button>
                @csrf
            </form>
        </div>
    </div>
@endsection