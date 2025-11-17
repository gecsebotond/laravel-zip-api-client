@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Counties</h1>

    @forelse ($counties as $county)
        <div class="mb-6 p-4 border rounded shadow">
            <h2 class="text-xl font-semibold">{{ $county['name'] }}</h2>
        </div>
    @empty
        <p>No counties found.</p>
    @endforelse
</div>
@endsection
