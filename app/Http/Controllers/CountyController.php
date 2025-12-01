<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountyController extends Controller
{
    public function index()
    {
        $response = Http::api()->get("counties");

        return view('counties.index', [
            'counties' => $response->json('data') ?? []
        ]);
    }

    public function create()
    {
        return view('counties.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        $token = session('api_token');

        $response = Http::api()->withToken($token)->post('counties', [
            'name' => $request->name,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Failed to create county.');
        }

        return redirect()->route('counties.index')->with('success', 'County added!');
    }

    public function edit($id)
    {
        $response = Http::api()->get("counties/$id");

        return view('counties.edit', [
            'county' => $response->json('data')
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $token = session('api_token');

        $response = Http::api()->withToken($token)->put("counties/$id", [
            'name' => $request->name,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Update failed.');
        }

        return redirect()->route('counties.index')->with('success', 'County updated!');
    }

    public function destroy($id)
    {
        $token = session('api_token');

        $response = Http::api()->withToken($token)->delete("counties/$id");

        if ($response->failed()) {
            return back()->withErrors('Delete failed.');
        }

        return redirect()->route('counties.index')->with('success', 'County deleted!');
    }

    public function downloadCsv($id)
    {
        $response = Http::api()->get("counties/$id");
        $county = $response->json();
    
        if (!$county) {
            return redirect()->back()->withErrors('County not found.');
        }
    
        $csvFile = fopen('php://memory', 'w');
    
        fputcsv($csvFile, ['county_id', 'county_name', 'place_id', 'place_name'], ";");
    
        $places = $county['data']['places'] ?? [];
        //var_dump($places);
    
        if (empty($places)) {
            fputcsv($csvFile, [$county['data']['id'], $county['data']['name'], '', ''], ";");
        } else {
            foreach ($places as $place) {
                fputcsv($csvFile, [
                    $county['data']['id'],
                    $county['data']['name'],
                    $place['id'],
                    $place['name']
                ], ";");
            }
        }
    
        rewind($csvFile);
        $csvData = stream_get_contents($csvFile);
        fclose($csvFile);
    
        return response($csvData, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="county_'.$id.'.csv"');
    }
    
    
}
