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
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('إضافة عقار جديد') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($properties->isEmpty())
                        <p class="text-gray-600">{{ __('لا توجد عقارات حالياً.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('العنوان') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('الفئة') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('السعر') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('النوع') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('الحالة') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('الإجراءات') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($properties as $property)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ Str::limit($property->title, 40) }}
                                                @if($property->images->first())
                                                    <img src="{{ asset('storage/' . $property->images->first()->path) }}" alt="صورة العقار" class="h-10 w-10 object-cover inline-block ml-2 rounded">
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $property->category->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($property->price) }} {{ $property->currency }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $property->type == 'for_sale' ? 'للبيع' : 'للإيجار' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $property->status == 'approved' ? 'bg-green-100 text-green-800' : ($property->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $property->status == 'approved' ? 'موافق عليه' : ($property->status == 'pending' ? 'قيد الانتظار' : ($property->status == 'sold' ? 'مباع' : 'مؤجر')) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.properties.edit', $property) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    {{ __('تعديل') }}
                                                </a>
                                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('هل أنت متأكد من رغبتك في حذف هذا العقار؟ سيتم حذف جميع الصور المرتبطة به أيضاً.') }}')">
                                                        {{ __('حذف') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>