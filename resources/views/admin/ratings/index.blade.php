<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة التقييمات والتعليقات') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('قائمة التقييمات') }}</h3>

                    {{-- Search and Filter Form --}}
                    <form action="{{ route('admin.ratings.index') }}" method="GET" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" name="search" placeholder="{{ __('بحث بالاسم/العنوان/التعليق...') }}" value="{{ request('search') }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <input type="number" name="property_id" placeholder="{{ __('معرف العقار') }}" value="{{ request('property_id') }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 min-w-0">
                            <input type="number" name="user_id" placeholder="{{ __('معرف المستخدم') }}" value="{{ request('user_id') }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 min-w-0">
                            <select name="stars" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('جميع التقييمات') }}</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" @selected(request('stars') == $i)>{{ $i }} {{ __('نجوم') }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('تصفية') }}
                            </button>
                            @if(request()->filled('search') || request()->filled('property_id') || request()->filled('user_id') || request()->filled('stars'))
                                <a href="{{ route('admin.ratings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('إزالة الفلاتر') }}</a>
                            @endif
                        </div>
                    </form>


                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('معرف') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('المستخدم') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('العقار') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('التقييم') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('التعليق') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('تاريخ الإنشاء') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('الإجراءات') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ratings as $rating)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $rating->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $rating->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $rating->property->title ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $rating->stars }} {{ __('نجوم') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs overflow-hidden text-ellipsis">
                                            {{ Str::limit($rating->comment, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $rating->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.ratings.edit', $rating) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                                {{ __('تعديل') }}
                                            </a>
                                            <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا التقييم؟') }}');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    {{ __('حذف') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{ __('لا يوجد تقييمات حالياً.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $ratings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>