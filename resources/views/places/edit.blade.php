
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Place</h1>

    <form method="POST" action="{{ route('places.update', $place['id']) }}">
        @csrf
        @method('PUT')

        <input type="text" name="name" value="{{ $place['name'] }}" class="border p-2 w-full mb-2">
        <input type="text" name="postal_code" value="{{ $place['postal_code'] }}" class="border p-2 w-full mb-2">

        <select name="county_id" class="border p-2 w-full mb-2">
            @foreach($counties as $county)
                <option value="{{ $county['id'] }}" {{ $county['id'] == $place['county_id'] ? 'selected' : '' }}>
                    {{ $county['name'] }}
                </option>
            @endforeach
        </select>

        <button class="bg-green-600 p-2 rounded">Update</button>
    </form>
</div>
@endsection
