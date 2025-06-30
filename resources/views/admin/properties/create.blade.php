<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إضافة عقار جديد') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.properties.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('عنوان العقار')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('الوصف والتفاصيل')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description" rows="5" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('الفئة')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('اختر الفئة') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="price" :value="__('السعر')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price')" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="currency" :value="__('العملة')" />
                                <x-text-input id="currency" class="block mt-1 w-full" type="text" name="currency" :value="old('currency', 'SYP')" required />
                                <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="location" :value="__('الموقع (مثال: دمشق - المزة)')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="type" :value="__('نوع العقار')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="for_sale" {{ old('type') == 'for_sale' ? 'selected' : '' }}>{{ __('للبيع') }}</option>
                                <option value="for_rent" {{ old('type') == 'for_rent' ? 'selected' : '' }}>{{ __('للإيجار') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('حالة العقار')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>{{ __('موافق عليه') }}</option>
                                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>{{ __('مباع') }}</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>{{ __('مؤجر') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="latitude" :value="__('خط العرض (اختياري)')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude')" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('خط الطول (اختياري)')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude')" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="images" :value="__('صور العقار (يمكن اختيار صور متعددة)')" />
                            <input id="images" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="images[]" multiple>
                            <p class="mt-1 text-sm text-gray-500">{{ __('صيغ الملفات المدعومة: JPG, PNG, GIF, SVG. الحد الأقصى 4MB لكل صورة.') }}</p>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" /> {{-- للتحقق من كل صورة على حدة --}}
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('إضافة عقار') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>