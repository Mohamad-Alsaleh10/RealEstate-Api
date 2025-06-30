<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل الفئة: ') }}{{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('الاسم')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $category->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('الوصف (اختياري)')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description">{{ old('description', $category->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label :value="__('الأيقونة الحالية')" />
                            @if ($category->icon)
                                <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover rounded-full mt-2">
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" id="delete_icon" name="delete_icon" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="delete_icon" class="ml-2 text-sm text-gray-600">{{ __('حذف الأيقونة الحالية') }}</label>
                                </div>
                            @else
                                <p class="text-gray-500 text-sm mt-2">{{ __('لا توجد أيقونة حالياً.') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <x-input-label for="icon" :value="__('تغيير الأيقونة (اختياري)')" />
                            <input id="icon" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="icon">
                            <p class="mt-1 text-sm text-gray-500">{{ __('صيغ الملفات المدعومة: JPG, PNG, GIF, SVG. الحد الأقصى 2MB.') }}</p>
                            <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('تحديث الفئة') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>