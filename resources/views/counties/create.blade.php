@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add Place</h1>

    <form method="POST" action="{{ route('places.store') }}" class="space-y-4">
        @csrf

        <input type="text" name="name" placeholder="Place name" class="border p-2 w-full">
        <input type="text" name="postal_code" placeholder="Postal code" class="border p-2 w-full">

        <select name="county_id" class="border p-2 w-full">
            <option value="">-- Select County --</option>
            @foreach($counties as $county)
                <option value="{{ $county['id'] }}">{{ $county['name'] }}</option>
            @endforeach
        </select>

        <button class="bg-blue-600 p-2 rounded">Save</button>
    </form>
</div>
@endsection
