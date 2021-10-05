@extends('layouts.main')
@section('title', 'Products')
@section('main')
    {{-- {{dd(Request::hasCookie('DEMO_API_KEY'))}} --}}
    <div class="row">
        <h3>My shop</h3>
        <div class="col-sm-12 pt-4 pb-4">
            @foreach ($products as $product)
                <div class="product">
                    <div class="wrapper">
                        <img src="{{$product->image}}" alt="">
                        <strong>{{$product->name}}</strong>
        
                        <strong>
                            <small>$</small>
                            <small>{{$product->price}}</small>
                        </strong>
                    </div>
                    @if (Cookie::get('DEMO_API_KEY'))
                        <button class="btn btn-sm btn-success btn-add-to-cart" data-route="{{route('add-item', $product->id)}}">
                            <i class="fas fa-shopping-cart"></i>
                            add to cart
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection