<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Key;
use Validator;
use Session;
use Facades\App\Http\Controllers\keyController;
use App\Mail\ApiCredentialsMail;

use Illuminate\Support\Facades\Mail;

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
            $validator = Validator::make($request->all(), [
                'key' => 'required|min:25|max:25|exists:keys,key'
            ]);

            if ($validator->fails())
            {
                $request->session()->flash('notification', [
                    'type' => 'warning',
                    'message' => $validator->errors()->messages(),
                ]);
                return redirect()->back()->withErrors($validator)->withInput();
            }

            return redirect()->route('products', $request->key);
        }
    }
    
    public function home(Request $request, $api_key = NULL)
    {
        $dbCart = Cart::where('api_key_id', getenv('DEMO_API_ID'))->first();

        $dbCart = ($dbCart && $dbCart->content) ? unserialize($dbCart->content) : null;
        $sessionCart = (session()->get('items')) ? session()->get('items') : null;
        
        $items = (!$sessionCart) ? unserialize($dbCart->content) : $sessionCart;

        
        $ids = array_map(function ($value) { return explode('_', $value)[1]; }, array_keys($items));
        $count = array_map(function ($value) {  return $value['qty']; }, $items);

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
        $success = Cart::where('api_key_id', getenv('DEMO_API_ID'))
                        ->first()
                        ->update([ 'email' => $request->email ]);
        # 3. Return with flash message
        if ($success)
        {
            $request->session()->flash('notification', [
                'type' => 'success',
                'message' => 'Cart info updated successfully. Please update your API credentials in the .env',
            ]);
            return back();
        }
    }

    public function delete(Request $request, $api_key)
    {
        $key = Key::where('key', $api_key)->first();


        $cart = Cart::where('api_key_id', $key->id)->first();
        if ($cart)
        {
            $success = $cart->delete();
            if ($success)
            {
                $request->session()->flash('notification', [
                    'type' => 'success',
                    'message' => 'Cart deleted successfully',
                ]);
                $key->delete();
                return redirect()->route('home');
            }
        }
        $request->session()->flash('notification', [
            'type' => 'warning',
            'message' => 'Invalid credentials.',
        ]);
        return back();
    }

    public function saveCartToDb(Request $request, $api_key)
    {
        $dbCart = Cart::where('api_key_id', getenv('DEMO_API_ID'));
        $sessionCart = session()->get('items');

        
        $success = Cart::where('api_key_id', getenv('DEMO_API_ID'))->first()->update([
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
