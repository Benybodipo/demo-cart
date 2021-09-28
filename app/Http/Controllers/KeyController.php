<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Validator;


class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id=null)
    {
        $keys = is_null($id) ? Key::all() : Key::whereId($id)->first();
        return response()->json(compact('keys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        # Generate a unique key
        $unique = false;
        while ($unique == false) {
            
            $key = Str::random(25);
            $unique = count(Key::where('key', $key)->get()) > 0 ? false : true; 
        }

        # Validation
        $validator = Validator::make(['key' => $key], [
            'key' => 'min:25|max:25|unique:keys'
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 401);

        # Create key
        $key = Key::create([ 'key' => $key ]);

        # Return Key info and message
        return response()->json([
            'key' => [
                'key' => $key->key, 
                'id' => $key->id
            ], 
            'message' => 'Key created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return response()->json(Key::whereId($id));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $key)
    {
        // return response()->json($request);
        # Validate
        $validator = Validator::make(compact('key'), [
            'key' => 'required|min:25|max:25|exists:keys,key'
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 401);

        # Update
        if ($_key = Key::where('key', $key)->first())
        {
            $_key->update([ 'key' => Str::random(25)]);
            return response()->json(['message' => 'Key successfully updated'], 200);
        }
        
        return response()->json(['message' => 'Unauthorized credentials'], 401);;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $key)
    {
        $validator = Validator::make(compact('key'), [
            'key' => 'required|min:25|max:25|exists:keys,key'
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 401);
        
        if ($_key = Key::where('key', $key)->first())
        {
            $_key->delete();
            return response()->json(['message' => "Key deleted"], 200);
        }
    }
}
