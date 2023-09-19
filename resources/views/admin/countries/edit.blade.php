{{--
    @extends('layouts.app')

    @section('content')
        admin.countries.edit template
    @endsection
--}}
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
                    Country details : {{ $country->name }}
                </h2>

                {{ $errors }}

                <form action="{{ route('admin.countries.update', $country) }}" method="post" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')
                    {{-- name --}}
                    <div>
                        <x-input-label for="name" :value="__('Name')"></x-input-label>

                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name', $country->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    {{-- slug --}}
                    <div>
                        <x-input-label for="slug" :value="__('Slug')"></x-input-label>
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full"
                            :value="old('slug', $country->slug)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>
                    {{-- lang --}}
                    <div>
                        <x-input-label for="lang" :value="__('Language')"></x-input-label>
                        <x-text-input id="lang" name="lang" type="text" class="mt-1 block w-full"
                            :value="old('lang', $country->lang)" />
                        <x-input-error class="mt-2" :messages="$errors->get('lang')" />
                    </div>

                    {{-- currency --}}
                    <div>
                        <x-input-label for="currency" :value="__('Currency')"></x-input-label>
                        <x-text-input id="currency" name="currency" type="text" class="mt-1 block w-full"
                            :value="old('currency', $country->currency)" />
                        <x-input-error class="mt-2" :messages="$errors->get('currency')" />
                    </div>
                    {{-- currency_symbol --}}
                    <div>
                        <x-input-label for="currency_symbol" :value="__('Currency Symbol')"></x-input-label>
                        <x-text-input id="currency_symbol" name="currency_symbol" type="text"
                            class="mt-1 block w-full" :value="old('currency_symbol', $country->currency_symbol)" />
                        <x-input-error class="mt-2" :messages="$errors->get('currency_symbol')" />
                    </div>

                    {{-- is_active : checkbox --}}
                    <div>
                        <x-input-label for="is_active" :value="__('Is active')"></x-input-label>
                        <x-checkbox-input id="is_active" name="is_active" :checked="old('is_active', $country->is_active)" />
                        <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                    </div>
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
