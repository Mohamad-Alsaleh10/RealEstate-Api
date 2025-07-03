<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة العقارات') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('قائمة العقارات') }}</h3>
                        <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('إضافة عقار جديد') }}
                        </a>
                    </div>

                    {{-- Search and Filter Form --}}
                    <form action="{{ route('admin.properties.index') }}" method="GET" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <input type="text" name="search" placeholder="{{ __('بحث بالعنوان أو الموقع أو الوصف أو اسم المستخدم...') }}" value="{{ request('search') }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 col-span-2">
                            <select name="status" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('جميع الحالات') }}</option>
                                <option value="pending" @selected(request('status') == 'pending')>{{ __('قيد الانتظار') }}</option>
                                <option value="approved" @selected(request('status') == 'approved')>{{ __('موافق عليه') }}</option>
                                <option value="rejected" @selected(request('status') == 'rejected')>{{ __('مرفوض') }}</option>
                                <option value="sold" @selected(request('status') == 'sold')>{{ __('مباع') }}</option>
                                <option value="rented" @selected(request('status') == 'rented')>{{ __('مؤجر') }}</option>
                            </select>
                            <select name="type" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('جميع الأنواع') }}</option>
                                <option value="for_sale" @selected(request('type') == 'for_sale')>{{ __('للبيع') }}</option>
                                <option value="for_rent" @selected(request('type') == 'for_rent')>{{ __('للإيجار') }}</option>
                            </select>
                            <select name="category_id" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('جميع الفئات') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('تصفية') }}
                                </button>
                                @if(request()->filled('search') || request()->filled('status') || request()->filled('type') || request()->filled('category_id'))
                                    <a href="{{ route('admin.properties.index') }}" class="text-sm text-gray-600 hover:text-gray-900 ml-2">{{ __('إزالة الفلاتر') }}</a>
                                @endif
                            </div>
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
                                        {{ __('العنوان') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('الفئة') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('المستخدم') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('السعر') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('النوع') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('الحالة') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('الإجراءات') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($properties as $property)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $property->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $property->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $property->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($property->price, 2) }} {{ $property->currency }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $property->type === 'for_sale' ? __('للبيع') : __('للإيجار') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $statusClass = '';
                                                switch ($property->status) {
                                                    case 'pending': $statusClass = 'bg-yellow-100 text-yellow-800'; break;
                                                    case 'approved': $statusClass = 'bg-green-100 text-green-800'; break;
                                                    case 'rejected': $statusClass = 'bg-red-100 text-red-800'; break;
                                                    case 'sold': $statusClass = 'bg-blue-100 text-blue-800'; break;
                                                    case 'rented': $statusClass = 'bg-purple-100 text-purple-800'; break;
                                                    default: $statusClass = 'bg-gray-100 text-gray-800'; break;
                                                }
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ __($property->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.properties.show', $property) }}" class="text-blue-600 hover:text-blue-900 ml-2">
                                                {{ __('عرض') }}
                                            </a>
                                            <a href="{{ route('admin.properties.edit', $property) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                                {{ __('تعديل') }}
                                            </a>
                                            @if ($property->status === 'pending')
                                                <form action="{{ route('admin.properties.approve', $property) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('هل أنت متأكد من الموافقة على هذا العقار؟') }}');">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 ml-2">
                                                        {{ __('موافقة') }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.properties.reject', $property) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('هل أنت متأكد من رفض هذا العقار؟') }}');">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        {{ __('رفض') }}
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا العقار؟') }}');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-600 hover:text-gray-900">
                                                    {{ __('حذف') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{ __('لا توجد عقارات حالياً.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $properties->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>