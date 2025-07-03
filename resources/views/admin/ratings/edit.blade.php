<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل التقييم') }} #{{ $rating->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.ratings.update', $rating) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="user_info" class="block text-sm font-medium text-gray-700">{{ __('المستخدم') }}</label>
                            <p class="mt-1 text-gray-900">{{ $rating->user->name ?? 'N/A' }} ({{ $rating->user->email ?? 'N/A' }})</p>
                        </div>

                        <div class="mb-4">
                            <label for="property_info" class="block text-sm font-medium text-gray-700">{{ __('العقار') }}</label>
                            <p class="mt-1 text-gray-900">{{ $rating->property->title ?? 'N/A' }}</p>
                            <a href="{{ route('admin.properties.show', $rating->property) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">{{ __('عرض تفاصيل العقار') }}</a>
                        </div>

                        <div class="mb-4">
                            <label for="stars" class="block text-sm font-medium text-gray-700">{{ __('عدد النجوم') }}</label>
                            <select name="stars" id="stars" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" @selected(old('stars', $rating->stars) == $i)>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('stars')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700">{{ __('التعليق') }}</label>
                            <textarea name="comment" id="comment" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('comment', $rating->comment) }}</textarea>
                            @error('comment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('تحديث التقييم') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>