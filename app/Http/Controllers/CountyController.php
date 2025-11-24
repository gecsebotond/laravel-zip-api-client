<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountyController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:8000/api/counties');

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

        $response = Http::withToken($token)->post('http://localhost:8000/api/counties', [
            'name' => $request->name,
        ]);

        if ($response->failed()) {
            return back()->withErrors('Failed to create county.');
        }

        return redirect()->route('counties.index')->with('success', 'County added!');
    }

    public function edit($id)
    {
        $response = Http::get("http://localhost:8000/api/counties/$id");

        return view('counties.edit', [
            'county' => $response->json('data')
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $token = session('api_token');

        $response = Http::withToken($token)->put("http://localhost:8000/api/counties/$id", [
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

        $response = Http::withToken($token)->delete("http://localhost:8000/api/counties/$id");

        if ($response->failed()) {
            return back()->withErrors('Delete failed.');
        }

        return redirect()->route('counties.index')->with('success', 'County deleted!');
    }

    public function downloadXml($id)
    {
        $token = session('api_token');
        $response = Http::get("http://localhost:8000/api/counties/$id");
        $county = $response->json();
    
        $xmlData = new \SimpleXMLElement('<?xml version="1.0"?><county></county>');
    
        $this->arrayToXml($county, $xmlData);
    
        return response($xmlData->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="county_'.$id.'.xml"');
    }
    
    private function arrayToXml(array $data, \SimpleXMLElement $xmlData)
    {
        foreach($data as $key => $value) {
            if(is_numeric($key)){
                $key = 'item';
            }
            if(is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars($value));
            }
        }
    }
    
    
}
