<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountyController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:8000/api/counties');

        if ($response->successful()) {
            $counties = $response->json('data'); // get the 'data' array
            return view('counties.index', compact('counties'));
        }

        // fallback if API fails
        return view('counties.index', ['counties' => []])->withErrors('Failed to fetch counties.');
    }
}
