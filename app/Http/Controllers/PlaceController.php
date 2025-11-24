<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CountyController;
use Illuminate\Support\Facades\Http;

class PlaceController extends Controller
{
    /**
     * Show the Places page with county dropdown
     */
    public function index()
    {
        $token = session('api_token');

        $response = Http::withToken($token)->get('http://localhost:8000/api/counties');

        $counties = $response->json('data') ?? [];

        return view('places.index', compact('counties'));
    }

    public function create()
    {
        // Need counties for dropdown
        $counties = Http::get('http://localhost:8000/api/counties')->json('data') ?? [];
        return view('places.create', compact('counties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'postal_code' => 'required',
            'county_id' => 'required|exists:counties,id',
        ]);

        $token = session('api_token');

        $response = Http::withToken($token)->post('http://localhost:8000/api/places', [
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
        $place = Http::withToken($token)->get("http://localhost:8000/api/places/$id")->json('data');
        $counties = Http::get('http://localhost:8000/api/counties')->json('data') ?? [];

        return view('places.edit', compact('place', 'counties'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'postal_code' => 'required',
            'county_id' => 'required|exists:counties,id',
        ]);

        $token = session('api_token');

        $response = Http::withToken($token)->put("http://localhost:8000/api/places/$id", [
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

        $response = Http::withToken($token)->delete("http://localhost:8000/api/places/$id");

        if ($response->failed()) {
            return back()->withErrors('Delete failed.');
        }

        return redirect()->route('places.index')->with('success', 'Place deleted!');
    }
}
