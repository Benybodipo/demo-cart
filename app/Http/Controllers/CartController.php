<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Key;
use Validator;
use Session;
use Facades\App\Http\Controllers\keyController;
use App\Mail\ApiCredentialsMail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

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

            # Create a new api key
            $new_key = keyController::create($request);
            $new_key = (object) $new_key->original['key'];

            # Create
            Cart::create([
                'name' => $request->name,
                'email' => $request->email,
                'api_key_id' => $new_key->id,
            ]);

            # Send credentials
            Mail::to('benybodipo@gmail.com')->send(new ApiCredentialsMail([
                'id' => $new_key->id,
                'user' => $request->email,
                'key' => $new_key->key,
            ]));

            # Send response
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
        
        if ($method == 'get')
        {
            return view('pages.access-cart');
        }
        else
        {
            # Validate API KEY
            $validator = Validator::make($request->all(), [
                'key' => 'required|min:25|max:25',
                'verified' => 'required|integer|min:1|max:1',
                'key_id' => 'required|integer',
            ]);

            if ($validator->fails())
            {
                $request->session()->flash('notification', [
                    'type' => 'warning',
                    'message' => $validator->errors()->messages(),
                ]);
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Verify that cart exists 
            $cart = Cart::where('api_key_id', $request->key_id)->first();
            if (!$cart)
            {
                $request->session()->flash('notification', [
                    'type' => 'warning',
                    'message' => 'Invalid crdentials',
                ]);
                return redirect()->back()->withInput();
            }

            # Save API KEY IN COOKIE
            Cookie::queue('DEMO_API_KEY', $request->key, 120);
            if (!session()->exists("user"))
            {
                $cart = Cart::where('api_key_id', $request->key_id)->first();

                session()->put("user", [
                    'name' => $cart->name,
                    'email' => $cart->email,
                    'id' => $cart->id
                ]);
            }

            return redirect()->route('products', $request->key);
        }
    }

    public function exitCart(Request $request)
    {
        Cookie::queue(Cookie::forget('DEMO_API_KEY'));
        session()->pull("items");
        session()->pull("user");

        return redirect()->route('home');
    }
    
    public function home(Request $request, $api_key = NULL)
    {
        $dbCart = Cart::where('api_key_id', session()->get('user.id'))->first();

        $dbCart = ($dbCart && $dbCart->content) ? unserialize($dbCart->content) : null;
        $sessionCart = (session()->get('items')) ? session()->get('items') : null;
         
        $items = (!$sessionCart) ? $dbCart : $sessionCart;

        if (!session()->exists('items'))
        {
            if (!is_null($items))
                session()->put("items", $items);
            else
                session()->put("items");
        }

        #validate more
        $ids = [];
        $count = [];

        if (!is_null($items))
        {
            $ids = array_map(function ($value) { return explode('_', $value)[1]; }, array_keys($items));
            $count = array_map(function ($value) {  return $value['qty']; }, $items);
        }

        $products = \App\Models\Product::whereIn('id', $ids)->get();
        return view('pages.cart')->with(compact('products'))
                            ->with(compact('items'))
                            ->with(compact('ids'))
                            ->with(compact('count'));
    }

    public function profile(Request $request, $api_key = NULL)
    {
        return view('pages.profile');
    }

    public function update(Request $request)
    {
        # 1. Validate input
        $validator = Validator::make($request->all(), [
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
        # 2. Update 
        $success = Cart::where('id', session()->get('user.id'))
                        ->first()
                        ->update([ 'email' => $request->email ]);
        # 3. Return with flash message
        if ($success)
        {
            session()->put('user.email', $request->email);
            $request->session()->flash('notification', [
                'type' => 'success',
                'message' => 'Cart info updated',
            ]);
            return back();
        }
    }

    public function delete(Request $request)
    {
        $cart = Cart::where('id', session()->get('user.id'))->first();
        
        if ($cart)
        {
            $success = $cart->delete();
            if ($success)
            {
                $request->session()->flash('notification', [
                    'type' => 'primary',
                    'message' => 'Cart deleted successfully',
                ]);

                Cookie::queue(Cookie::forget('DEMO_API_KEY'));
                session()->pull("items.id_".session()->get('user.id'));
                session()->pull("user");
            }
        }

        $request->session()->flash('notification', [
            'type' => 'warning',
            'message' => 'Invalid credentials.',
        ]);
        return back();
    }

    public function saveCartToDb(Request $request)
    {
        $dbCart = Cart::where('id', session()->get('user.id'));
        $sessionCart = session()->get('items');

        
        $success = Cart::where('api_key_id', session()->get('user.id'))->first()->update([
            'content' => serialize($sessionCart)
        ]);

        if ($success)
        {
            $request->session()->flash('notification', [
                'type' => 'success',
                'message' => 'Cart successfully saved to database.',
            ]);
            return back();
        }
    }

    public function addItem(Request $request, $id)
    {
        if (!session()->exists("items.id_{$id}"))
        {
            session()->put("items.id_{$id}", ['qty' => 1]);
        }

        return session()->get('items');
    }

    public function updateItem(Request $request,  $id)
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

    public function deleteItem(Request $request, $id)
    {
        if (session()->exists("items.id_{$id}"))
        {
            session()->pull("items.id_{$id}");
            return response()->json(['count' => count(session()->get('items'))]);
        }
    }
}
