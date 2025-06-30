<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل العقار: ') }}{{ $property->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.properties.update', $property) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" :value="__('عنوان العقار')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $property->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('الوصف والتفاصيل')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description" rows="5" required>{{ old('description', $property->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('الفئة')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('اختر الفئة') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $property->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="price" :value="__('السعر')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $property->price)" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="currency" :value="__('العملة')" />
                                <x-text-input id="currency" class="block mt-1 w-full" type="text" name="currency" :value="old('currency', $property->currency)" required />
                                <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="location" :value="__('الموقع (مثال: دمشق - المزة)')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $property->location)" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="type" :value="__('نوع العقار')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="for_sale" {{ old('type', $property->type) == 'for_sale' ? 'selected' : '' }}>{{ __('للبيع') }}</option>
                                <option value="for_rent" {{ old('type', $property->type) == 'for_rent' ? 'selected' : '' }}>{{ __('للإيجار') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('حالة العقار')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('status', $property->status) == 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                                <option value="approved" {{ old('status', $property->status) == 'approved' ? 'selected' : '' }}>{{ __('موافق عليه') }}</option>
                                <option value="sold" {{ old('status', $property->status) == 'sold' ? 'selected' : '' }}>{{ __('مباع') }}</option>
                                <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>{{ __('مؤجر') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="latitude" :value="__('خط العرض (اختياري)')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude', $property->latitude)" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('خط الطول (اختياري)')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude', $property->longitude)" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label :value="__('الصور الحالية')" />
                            @if ($property->images->isNotEmpty())
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-2">
                                    @foreach ($property->images as $image)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $image->path) }}" alt="صورة العقار" class="w-full h-32 object-cover rounded-lg shadow-sm">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                                <button type="button" onclick="confirmDeleteImage({{ $image->id }})" class="text-white bg-red-600 hover:bg-red-700 p-2 rounded-full mx-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <input type="radio" name="primary_image_id" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }} class="form-radio text-indigo-600 ml-1" title="تعيين كصورة رئيسية">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-sm text-gray-500">{{ __('حدد زر الراديو لتعيين الصورة الرئيسية. استخدم أيقونة سلة المهملات لحذف الصورة.') }}</p>
                            @else
                                <p class="text-gray-500 text-sm mt-2">{{ __('لا توجد صور حالياً لهذا العقار.') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <x-input-label for="images" :value="__('إضافة صور جديدة (يمكن اختيار صور متعددة)')" />
                            <input id="images" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="images[]" multiple>
                            <p class="mt-1 text-sm text-gray-500">{{ __('صيغ الملفات المدعومة: JPG, PNG, GIF, SVG. الحد الأقصى 4MB لكل صورة.') }}</p>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('تحديث العقار') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-image-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDeleteImage(imageId) {
            if (confirm('{{ __("هل أنت متأكد من رغبتك في حذف هذه الصورة؟") }}')) {
                const form = document.getElementById('delete-image-form');
                form.action = '{{ url("admin/property-images") }}/' + imageId; // Use url() for dynamic routes
                form.submit();
            }
        }
    </script>
</x-app-layout>