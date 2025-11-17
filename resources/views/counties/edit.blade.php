@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit County</h1>

    <form method="POST" action="{{ route('counties.update', $county['id']) }}">
        @csrf
        @method('PUT')

        <input type="text" name="name"
               value="{{ $county['name'] }}"
               class="border p-2 w-full mb-4">

        <button class="bg-green-600 p-2 rounded">Update</button>
    </form>
</div>
@endsection
