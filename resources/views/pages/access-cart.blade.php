@extends('layouts.main')
@section('title', 'Request API Key')
@section('main')
    <div class="row">
        <div class="pt-4 pb-4">
            @include('includes.flash-message')
            <form action="{{route('access-cart')}}" data-action="{{route('verify-key')}}" method="POST" id="form-access-cart">
                <h3 class="text-center mb-4">Access Cart</h3>
                <br>
                <div class="form-group">
                    <input type="text" class="form-control" name="key" value="{{old('key')}}" placeholder="Enter you API Access key">
                    <input type="hidden" name="verified" class="verified">
                    <input type="hidden" name="key_id" class="key_id">
                </div>
                <button class="btn btn-success btn-block" id="btn-access-cart">
                    Access Cart
                </button>
                @csrf
            </form>
        </div>
    </div>
@endsection