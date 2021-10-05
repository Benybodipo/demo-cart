@extends('layouts.main')
@section('title', 'Products')
@section('main')
    <div class="row">
        <div class="col-sm-12 pt-4 pb-4">
            @include('includes.flash-message')
            <form action="{{route('update-cart-info', request()->route('api_key'))}}" method="POST" id="delete-account-form" >
                <h3 class="text-center mb-4">My Cart profile</h3>
                <br>
                <div class="form-group">
                    <strong>API KEY:</strong>
                    <span>{{Cookie::get('DEMO_API_KEY')}}</span>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" value="{{old('email', session()->get('user.email'))}}">
                </div>
                <button class="btn btn-success btn-block">
                    Update
                </button>
                <br>
                <br>
                <a class="btn btn-danger" id="btn-delete-cart" style="color: white;" href="{{route('delete-cart')}}" data-href="{{route('delete-key', Cookie::get('DEMO_API_KEY'))}}">
                    Delete Cart
                </a>
                @csrf
            </form>
        </div>
    </div>
@endsection