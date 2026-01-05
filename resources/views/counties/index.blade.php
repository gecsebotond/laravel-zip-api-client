@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    @if(session('success'))
        <div id="flash-message" class="bg-green-200 text-green-800 p-2 rounded mb-4 flex">
            {{ session('success') }}
            <button id="closeButton" class="ml-auto" onClick="close">&#10006;</button>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flash = document.getElementById('flash-message');
            const closeButton = document.getElementById('closeButton');

            setTimeout(() => {
                flash.remove();
            }, 3000);

            closeButton.addEventListener('click', () => {
                flash?.remove();
            });
        });
        </script>

    @endif



    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Counties</h1>

        @auth
            <a href="{{ route('counties.create') }}"
               class="px-4 py-2 bg-blue-600 rounded shadow hover:bg-blue-700">
               + Add County
            </a>
        @endauth
    </div>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 border-b">
                    <th class="p-3 font-semibold text-gray-700">County Name</th>
                    <th class="p-3 font-semibold text-gray-700 w-1/3">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($counties as $county)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">
                            {{ $county['name'] }}
                        </td>

                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" class="px-3 py-1 bg-green-600 rounded shadow hover:bg-green-700 text-white flex items-center gap-2">
                                        Download
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg z-50">
                                        <a href="{{ route('counties.downloadCsv', $county['id']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Download CSV
                                        </a>

                                        <a href="{{ route('counties.downloadPdf', $county['id']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Download PDF
                                        </a>
                                    </div>
                                </div>
                                @auth
                                    {{-- Edit --}}
                                    <a href="{{ route('counties.edit', $county['id']) }}"
                                       class="px-3 py-1 bg-yellow-500 rounded shadow hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('counties.destroy', $county['id']) }}" class = "bg-red-500 rounded shadow hover:bg-red-600" method="POST"
                                          onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 rounded shadow">
                                            Delete
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="p-3 text-center text-gray-500">
                            No counties found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
