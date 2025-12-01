<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CountyController;
use Illuminate\Support\Facades\Http;

class PlaceController extends Controller
{
    public function index()
    {
        $token = session('api_token');

        $response = Http::api()->withToken($token)->get('counties');

        $counties = $response->json('data') ?? [];

        return view('places.index', compact('counties'));
    }

    public function create()
    {
        $counties = Http::api()->get('counties')->json('data') ?? [];
        return view('places.create', compact('counties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'postal_code' => 'required',
            'county_id' => 'required',
        ]);

        $token = session('api_token');

        $response = Http::api()->withToken($token)->post('places', [
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'county_id' => $request->county_id,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Failed to create place.');
        }

        return redirect()->route('places.index')->with('success', 'Place added!');
    }


    public function edit($id)
    {
        $token = session('api_token');
        $response = Http::api()->withToken($token)->get("places/$id");
        $place = $response->json();
        $counties = Http::api()->get('counties')->json('data') ?? [];
        return view('places.edit', compact('place', 'counties'));
    }

    

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'postal_code' => 'required',
            'county_id' => 'required',
        ]);

        $token = session('api_token');

        $response = Http::api()->withToken($token)->put("places/$id", [
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'county_id' => $request->county_id,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Update failed.');
        }

        return redirect()->route('places.index')->with('success', 'Place updated!');
    }

    public function destroy($id)
    {
        $token = session('api_token');

        $response = Http::api()->withToken($token)->delete("places/$id");

        if ($response->failed()) {
            return back()->withErrors('Delete failed.');
        }

        return redirect()->route('places.index')->with('success', 'Place deleted!');
    }
}
