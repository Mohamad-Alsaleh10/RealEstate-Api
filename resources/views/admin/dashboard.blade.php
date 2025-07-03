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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('مرحباً بك !') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-blue-100 p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition duration-300 ease-in-out">
                            <h4 class="text-blue-800 text-3xl font-bold mb-2">{{ $propertiesCount ?? 0 }}</h4>
                            <p class="text-blue-700 text-lg">{{ __('إجمالي العقارات') }}</p>
                            <a href="{{ route('admin.properties.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 text-sm font-semibold">{{ __('إدارة العقارات') }} &rarr;</a>
                        </div>

                        <div class="bg-orange-100 p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition duration-300 ease-in-out">
                            <h4 class="text-orange-800 text-3xl font-bold mb-2">{{ $pendingPropertiesCount ?? 0 }}</h4>
                            <p class="text-orange-700 text-lg">{{ __('عقارات قيد الانتظار') }}</p>
                            <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" class="mt-3 inline-block text-orange-600 hover:text-orange-800 text-sm font-semibold">{{ __('مراجعة العقارات') }} &rarr;</a>
                        </div>

                        <div class="bg-green-100 p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition duration-300 ease-in-out">
                            <h4 class="text-green-800 text-3xl font-bold mb-2">{{ $categoriesCount ?? 0 }}</h4>
                            <p class="text-green-700 text-lg">{{ __('إجمالي الفئات') }}</p>
                            <a href="{{ route('admin.categories.index') }}" class="mt-3 inline-block text-green-600 hover:text-green-800 text-sm font-semibold">{{ __('إدارة الفئات') }} &rarr;</a>
                        </div>

                        <div class="bg-purple-100 p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition duration-300 ease-in-out">
                            <h4 class="text-purple-800 text-3xl font-bold mb-2">{{ $usersCount ?? 0 }}</h4>
                            <p class="text-purple-700 text-lg">{{ __('إجمالي المستخدمين') }}</p>
                            <a href="{{ route('admin.users.index') }}" class="mt-3 inline-block text-purple-600 hover:text-purple-800 text-sm font-semibold">{{ __('إدارة المستخدمين') }} &rarr;</a>
                        </div>

                        <div class="bg-red-100 p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition duration-300 ease-in-out">
                            <h4 class="text-red-800 text-3xl font-bold mb-2">{{ $ratingsCount ?? 0 }}</h4>
                            <p class="text-red-700 text-lg">{{ __('إجمالي التقييمات') }}</p>
                            <a href="{{ route('admin.ratings.index') }}" class="mt-3 inline-block text-red-600 hover:text-red-800 text-sm font-semibold">{{ __('إدارة التقييمات') }} &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>