@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add County</h1>

    <form method="POST" action="{{ route('counties.store') }}" class="space-y-4">
        @csrf

        <input type="text" name="name" placeholder="County name"
               class="border p-2 w-full">

        <button class="bg-blue-600 p-2 rounded">Save</button>
    </form>
</div>
@endsection
