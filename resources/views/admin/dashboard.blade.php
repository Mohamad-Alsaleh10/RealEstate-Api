<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم الأدمن') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('مرحباً بك، أيها المسؤول!') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-100 p-4 rounded-lg shadow-sm text-center">
                            <h4 class="text-blue-800 text-2xl font-bold">{{ $propertiesCount ?? 0 }}</h4>
                            <p class="text-blue-700">{{ __('إجمالي العقارات') }}</p>
                            <a href="{{ route('admin.properties.index') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800 text-sm">{{ __('إدارة العقارات') }} &rarr;</a>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg shadow-sm text-center">
                            <h4 class="text-green-800 text-2xl font-bold">{{ $categoriesCount ?? 0 }}</h4>
                            <p class="text-green-700">{{ __('إجمالي الفئات') }}</p>
                            <a href="{{ route('admin.categories.index') }}" class="mt-2 inline-block text-green-600 hover:text-green-800 text-sm">{{ __('إدارة الفئات') }} &rarr;</a>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg shadow-sm text-center">
                            <h4 class="text-purple-800 text-2xl font-bold">{{ $usersCount ?? 0 }}</h4>
                            <p class="text-purple-700">{{ __('إجمالي المستخدمين') }}</p>
                            {{-- يمكنك إضافة رابط لإدارة المستخدمين هنا لاحقاً إذا أردت --}}
                            <span class="mt-2 inline-block text-purple-600 text-sm">{{ __('إدارة المستخدمين قريباً') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
