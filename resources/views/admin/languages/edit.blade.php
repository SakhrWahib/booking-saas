@extends('layouts.app')

@section('content')
<div class="py-6 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                {{ __('web.languages.modals.edit.title') }}
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('web.languages.management_description') }}
            </p>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-sm">
            <form action="{{ route('admin.languages.update', $language->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Language Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">
                            {{ __('web.languages.forms.code.label') }}
                        </label>
                        <input type="text" name="code" id="code" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            value="{{ old('code', $language->code) }}" 
                            placeholder="{{ __('web.languages.forms.code.placeholder') }}"
                            required>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Language Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            {{ __('web.languages.forms.name.label') }}
                        </label>
                        <input type="text" name="name" id="name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            value="{{ old('name', $language->name) }}" 
                            placeholder="{{ __('web.languages.forms.name.placeholder') }}"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Native Name -->
                    <div>
                        <label for="native_name" class="block text-sm font-medium text-gray-700">
                            {{ __('web.languages.forms.native_name.label') }}
                        </label>
                        <input type="text" name="native_name" id="native_name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            value="{{ old('native_name', $language->native_name) }}" 
                            placeholder="{{ __('web.languages.forms.native_name.placeholder') }}"
                            required>
                        @error('native_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Flag -->
                    <div>
                        <label for="flag" class="block text-sm font-medium text-gray-700">
                            {{ __('web.languages.forms.flag.label') }}
                        </label>
                        <div class="mt-1 flex items-center">
                            @if($language->flag)
                                <div class="relative {{ App::getLocale() == 'ar' ? 'ml-3' : 'mr-3' }}">
                                    <img src="{{ asset('storage/' . $language->flag) }}" 
                                         alt="{{ $language->name }}" 
                                         class="h-12 w-12 object-cover rounded-lg">
                                </div>
                            @endif
                            <input type="file" name="flag" id="flag" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        @error('flag')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">
                            {{ __('web.languages.forms.status.label') }}
                        </label>
                        <select name="is_active" id="is_active" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="1" {{ old('is_active', $language->is_active) ? 'selected' : '' }}>
                                {{ __('web.languages.forms.status.active') }}
                            </option>
                            <option value="0" {{ !old('is_active', $language->is_active) ? 'selected' : '' }}>
                                {{ __('web.languages.forms.status.inactive') }}
                            </option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Default Language -->
                    <div>
                        <label for="is_default" class="block text-sm font-medium text-gray-700">
                            {{ __('web.languages.forms.is_default.label') }}
                        </label>
                        <select name="is_default" id="is_default" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="1" {{ old('is_default', $language->is_default) ? 'selected' : '' }}>
                                {{ __('web.languages.forms.is_default.yes') }}
                            </option>
                            <option value="0" {{ !old('is_default', $language->is_default) ? 'selected' : '' }}>
                                {{ __('web.languages.forms.is_default.no') }}
                            </option>
                        </select>
                        @error('is_default')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-3 rtl:space-x-reverse">
                    <a href="{{ route('admin.languages.index') }}" 
                       class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('web.modals.cancel') }}
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('web.modals.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
