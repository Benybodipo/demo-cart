<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Key;
use Validator;
use Session;

class CartController extends Controller
{
    public function requestApiKey(Request $request)
    {
        $method = strtolower($request->method());
        
        if ($method == 'get')
            return view('pages.request-api-key');
        else
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'email' => 'required|email|unique:carts'
            ]);

            if ($validator->fails())
            {
                $request->session()->flash('notification', [
                    'type' => 'danger',
                    'message' => $validator->errors()->messages(),
                ]);
                return redirect()->back()->withInput();
            }
            $request->session()->flash('notification', [
                'type' => 'success',
                'message' => "Request sent, please check your inbox.",
            ]);
            return redirect()->route('access-cart');
        }

    }


    public function accessCart(Request $request)
    {
        $method = strtolower($request->method());
        // dd($method);
        if ($method == 'get')
        {
            return view('pages.access-cart');
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'key' => 'required|min:25|max:25|exists:keys,key'
            ]);

            if ($validator->fails())
            {
                $data = ([
                    'type' => 'warning',
                    'message' => $validator->errors()->messages(),
                ]);
                $request->session()->flash('notification', $data);
                return redirect()->back()->withErrors($validator)->withInput();
            }

            return redirect()->route('products', $request->key);
        }
    }
    
    public function home(Request $request, $api_key = NULL)
    {
        return view('pages.cart')->with('products', Product::paginate(10));
    }

    public function profile(Request $request, $api_key = NULL)
    {
        return view('pages.profile');
    }

    public function update(Request $request)
    {
        $cart = Cart::whereId($request->id);

        dd($cart);
    }

    public function delete(Request $request, $id)
    {
        $cart = Cart::whereId($request->id);
        dd($cart);
    }

    public function addItem(Request $request, $api_key, $id)
    {
        if (!session()->exists("items.id_{$id}"))
        {
            session()->put("items.id_{$id}", ['qty' => 1]);
        }

        return session()->get('items');
    }

    public function updateItem(Request $request, $api_key, $id)
    {
        if (session()->exists("items.id_{$id}"))
        {
            session()->put("items.id_{$id}.qty", $request->qty);
            $product = Product::whereId($id)->first();
        }
        return response()->json([
            'qty' => $request->qty,
            'sub-total' => $product->price * $request->qty
        ]);
    }

    public function deleteItem(Request $request, $api_key, $id)
    {
        if (session()->exists("items.id_{$id}"))
        {
            session()->pull("items.id_{$id}");
            return response()->json(['count' => count(session()->get('items'))]);
        }
    }
}
