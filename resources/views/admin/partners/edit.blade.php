@extends('layouts.admin')

@section('title', 'Edit Partner')
@section('breadcrumb', 'Edit Partner')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Partner: {{ $partner->name }}</h2>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
            <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Partner Name*</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $partner->name) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                        <input type="url" name="url" id="url" value="{{ old('url', $partner->url) }}"
                            placeholder="https://..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                        <div class="flex items-center gap-4 mb-2">
                            @if($partner->logo_path)
                                <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->name }}"
                                    class="h-10 w-auto bg-gray-50 p-1 rounded">
                            @endif
                            <input type="file" name="logo" id="logo"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <p class="text-xs text-gray-400">Recommended size: 200x80px (transparent PNG/SVG)</p>
                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order"
                                value="{{ old('sort_order', $partner->sort_order) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                        <div class="flex items-center pt-6">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $partner->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">Is Active</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t">
                        <a href="{{ route('admin.partners.index') }}"
                            class="px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">Cancel</a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">Update
                            Partner</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection