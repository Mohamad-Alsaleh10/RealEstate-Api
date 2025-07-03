<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category Details') }} - {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">{{ $category->name }}</h3>

                    <div class="mb-4">
                        <p><strong>{{ __('Description') }}:</strong> {{ $category->description ?? 'N/A' }}</p>
                        <p><strong>{{ __('Number of Properties') }}:</strong> {{ $category->properties_count ?? 0 }}</p>
                    </div>

                    {{-- يمكنك إضافة قائمة بالعقارات التابعة لهذه الفئة هنا إذا قمت بتحميلها --}}
                    @if(isset($category->properties) && $category->properties->isNotEmpty())
                        <h4 class="text-xl font-semibold mb-3">{{ __('Properties in this Category') }}</h4>
                        <ul class="list-disc pl-5">
                            @foreach($category->properties as $property)
                                <li>
                                    <a href="{{ route('admin.properties.show', $property->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $property->title }} ({{ $property->location }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @elseif (isset($category->properties_count) && $category->properties_count > 0)
                        <p>{{ __('This category has properties, but they are not loaded in this view.') }}</p>
                    @else
                        <p>{{ __('No properties associated with this category yet.') }}</p>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Back to Categories') }}
                        </a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>