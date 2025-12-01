@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8" x-data="placesPage()">

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

    @auth
    <div class="mb-6">
        <a href="{{ route('places.create') }}" class="px-4 py-2 bg-green-500 rounded-md hover:bg-green-600">
            Add New Place
        </a>
    </div>
    @endauth

    <div class="mb-6">
        <label for="county" class="block font-medium text-gray-700 mb-2">Select County</label>
        <div class="flex items-center space-x-2">
            <select id="county" x-model="selectedCounty" class="border-gray-300 rounded-md shadow-sm" 
                x-init="selectedCounty='{{ $counties[0]['id'] ?? '' }}'">
                @foreach($counties as $county)
                    <option value="{{ $county['id'] }}">{{ $county['name'] }}</option>
                @endforeach
            </select>

            <button 
                @click="fetchInitials" 
                class="px-4 py-2 bg-blue-500 rounded-md hover:bg-blue-600">
                Load Initials
            </button>
        </div>
    </div>

    <div class="mb-6" x-show="initials.length > 0">
        <span class="font-medium mr-2">Initials:</span>
        <template x-for="letter in initials" :key="letter">
            <button 
                @click="fetchPlacesByInitial(letter)"
                x-text="letter"
                :class="{
                    'bg-blue-500 text-white': letter === selectedLetter,
                    'bg-gray-200': letter !== selectedLetter
                }"
                class="px-3 py-1 rounded-md mr-1 mb-1">
            </button>
        </template>
    </div>

    <div x-show="places.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ZIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    @auth
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    @endauth
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="place in places" :key="place.id">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="place.id"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="place.postal_code"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="place.name"></td>

                        @auth
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a :href="`/places/${place.id}/edit`" class="text-blue-600 hover:underline mr-2">Edit</a>
                            
                            <form :action="`/places/${place.id}`" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline"
                                    @click.prevent="if(confirm('Delete this place?')) { $el.closest('form').submit() }">
                                    Delete
                                </button>
                            </form>
                        </td>
                        @endauth

                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div x-show="selectedLetter && places.length === 0" class="mt-4 text-gray-500">
        No places found for this initial.
    </div>

</div>

<script>
function placesPage() {
    return {
        selectedCounty: '',
        selectedLetter: '',
        initials: [],
        places: [],

        fetchInitials() {
            this.initials = [];
            this.places = [];
            this.selectedLetter = '';

            if (!this.selectedCounty) {
                alert('Please select a county first.');
                return;
            }

            fetch(`/places/${this.selectedCounty}/initials`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.initials = data.data;
                    } else {
                        alert('Failed to load initials.');
                    }
                })
                .catch(() => alert('Error fetching initials.'));
        },

        fetchPlacesByInitial(letter) {
            if (!this.selectedCounty) return;

            this.selectedLetter = letter;
            this.places = [];

            fetch(`/places/${this.selectedCounty}/initials/${letter}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.places = data.data;
                    } else {
                        alert('Failed to load places.');
                    }
                })
                .catch(() => alert('Error fetching places.'));
        }
    }
}
</script>
@endsection
