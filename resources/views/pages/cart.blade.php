@extends('layouts.main')
@section('title', 'My Cart')
@section('main')
    <div class="row cart-row">
        @include('includes.flash-message')
        @if (!is_null($items) && count($items))
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
                                        <input type="number" name="" min="1" value='{{$items["id_{$product->id}"]["qty"]}}' id="" style="max-width: 50px" class="form-control form-control-sm update-quantity" data-route="{{route('update-item', $product->id)}}" >
                                    </td>
                                    <td class="subtotal">
                                        $
                                        <span>
                                            {{$product->price * $items["id_{$product->id}"]["qty"]}}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger delete-item"  data-route="{{route('delete-item',$product->id)}}">
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
                                    <form action="{{route('save-cart-to-db', request()->route('api_key'))}}" method="POST">
                                        @csrf
                                        <button class="btn btn-success">Save Cart</button>
                                    </form>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
        @else
            <h3 class="text-center">Nothing in your cart!</h3>
        @endif
    </div>
@endsection