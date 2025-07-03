<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Property Details') }} - {{ $property->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">{{ $property->title }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p><strong>{{ __('Category') }}:</strong> {{ $property->category->name ?? 'N/A' }}</p>
                            <p><strong>{{ __('Price') }}:</strong> {{ $property->price }} {{ $property->currency }}</p>
                            <p><strong>{{ __('Type') }}:</strong> {{ ucfirst(str_replace('_', ' ', $property->type)) }}</p>
                            <p><strong>{{ __('Location') }}:</strong> {{ $property->location }}</p>
                            <p><strong>{{ __('Status') }}:</strong>
                                @if($property->status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ __('Approved') }}
                                    </span>
                                @elseif($property->status == 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ __('Pending') }}
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                @endif
                            </p>
                            <p><strong>{{ __('Added by') }}:</strong> {{ $property->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p><strong>{{ __('Description') }}:</strong></p>
                            <p>{{ $property->description }}</p>
                            @if($property->latitude && $property->longitude)
                                <p><strong>{{ __('Coordinates') }}:</strong> {{ $property->latitude }}, {{ $property->longitude }}</p>
                            @endif
                        </div>
                    </div>

                    @if($property->images->isNotEmpty())
                        <h4 class="text-xl font-semibold mb-3">{{ __('Images') }}</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($property->images as $image)
                                <div class="w-full h-48 overflow-hidden rounded-lg shadow-md">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $property->title }} Image" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>{{ __('No images available for this property.') }}</p>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Back to List') }}
                        </a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>