{{--
    @extends('layouts.app')

    @section('content')
        admin.countries.show template
    @endsection
--}}




<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Country') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-3xl font-bold mb-4">
                        Country details : {{ $country->name }}
                    </h2>

                    <table class="w-full border border-gray-200">
                        <thead class="text-gray-600 uppercase text-sm">
                            <tr class="text-left">
                                <th class="py-2 px-4">Name</th>
                                <td class="py-2 px-4">
                                    <a href="{{ route('admin.countries.edit', $country->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        {{ $country->name }}
                                    </a>

                                </td>
                            </tr>
                            <tr class="text-left">
                                <th class="py-2 px-4">Language</th>
                                <td class="py-2 px-4">{{ $country->lang }}</td>
                            </tr>
                            <tr class="text-left">
                                <th class="py-2 px-4">Currency</th>
                                <td class="py-2 px-4">{{ $country->currency }}</td>
                            </tr>
                            <tr class="text-left">
                                <th class="py-2 px-4">Currency Symbol</th>
                                <td class="py-2 px-4">{{ $country->currency_symbol }}</td>
                            </tr>
                            </tbody>
                    </table>

                    {{-- all countries --}}
                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('admin.countries.index') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-dark font-bold py-2 px-4 rounded display-inline-block ">
                            All Countries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
