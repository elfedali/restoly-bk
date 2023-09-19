<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Countries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-3xl font-bold mb-4">
                    All Countries
                </h2>

                <div class="mb-4 flex justify-end">
                    <a href="{{ route('admin.countries.create') }}"
                        class="bg-blue-500 hover:bg-blue-700  text-white font-bold py-2 px-4 rounded">
                        Add new country
                    </a>
                </div>

                <table class="w-full border border-gray-200">
                    <thead class="text-gray-600 uppercase text-sm">
                        <tr class="text-left">
                            <th class="py-2 px-4">Name</th>
                            <th class="py-2 px-4">Language</th>
                            <th class="py-2 px-4">Currency</th>
                            <th class="py-2 px-4">Currency Symbol</th>
                            <th class="py-2 px-4">Is Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($countries as $country)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-2 px-4">
                                    <a href="{{ route('admin.countries.edit', $country->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        {{ $country->name }}
                                    </a>

                                </td>
                                <td class="py-2 px-4">{{ $country->lang }}</td>
                                <td class="py-2 px-4">{{ $country->currency }}</td>
                                <td class="py-2 px-4">{{ $country->currency_symbol }}</td>
                                @if ($country->is_active)
                                    <td class="py-2 px-4">
                                        <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">
                                            Active
                                        </span>
                                    </td>
                                @else
                                    <td class="py-2 px-4">
                                        <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">
                                            Inactive
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
