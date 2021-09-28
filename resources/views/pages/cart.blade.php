@extends('layouts.main')
@section('title', 'My Cart')
@section('main')

    @if (!request()->route('api_key'))
        <form action="" method="POST" id="request-key-form">
            <h3 class="text-center mb-4">Access Cart</h3>
            <br>
            <div class="form-group">
                <input type="text" class="form-control" name="key" value="" placeholder="Enter you API Access key">
            </div>
            <button class="btn btn-success btn-block">
                Access Cart
            </button>
            @csrf
        </form>
    @else
        <div class="row cart-row">
            @if (session()->get('items'))
                @php
                    $ids = array_map(function ($value) { return explode('_', $value)[1]; }, array_keys(session()->get('items')));
                    $count = array_map(function ($value) { 
                        return $value['qty']; 
                    }, session()->get('items'));

                    $products = \App\Models\Product::whereIn('id', $ids)->get();
                @endphp
                <div class="pt-4 pb-4 cart-container">
                    <h3>My cart</h3>
                    <table class="table" id="cart-table"> 
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Sub total</th>
                            <th></th>
                        </tr>
        
                        <tbody>
                            <form action="">
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <img src="{{$product->image}}" alt="">
                                        </td>
                                        <td>
                                            {{$product->name}}
                                        </td>
                                        <td>
                                            ${{$product->price}}
                                        </td>
                                        <td>
                                            <input type="number" name="" min="1" value='{{session()->get("items.id_{$product->id}.qty")}}' id="" style="max-width: 50px" class="form-control form-control-sm update-quamtity" data-route="{{route('update-item', [request()->route('api_key'), $product->id])}}" >
                                        </td>
                                        <td class="subtotal">
                                            $
                                            <span>
                                                {{$product->price * session()->get("items.id_{$product->id}.qty")}}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger delete-item"  data-route="{{route('delete-item', [request()->route('api_key'), $product->id])}}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php $total += ($product->price * session()->get("items.id_{$product->id}.qty")); ?>  
                                @endforeach
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="count">{{array_sum($count)}}</td>
                                    <td>
                                        $<span class="total">{{$total}}</span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <button class="btn btn-success">Save Cart</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            @else
                <h3 class="text-center">Nothing in your cart!</h3>
            @endif
        </div>
    @endif
@endsection