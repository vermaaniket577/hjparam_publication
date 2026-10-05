@extends('layouts.admin')

@section('title', 'Add New User')
@section('breadcrumb', 'Users / Create')

@section('content')
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden p-6" x-data="{ role: '{{ old('role', 'editor') }}' }">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Create New User</h2>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" x-model="role"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="author">Author</option>
                    <option value="reviewer">Reviewer</option>
                    <option value="editor">Editor</option>
                    <option value="sub_admin">Sub Admin</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Journal Editorial Team Assignment (Visible when role is Editor) -->
            <div x-show="role === 'editor'" x-transition 
                 class="p-4 rounded-xl bg-blue-50/80 border border-blue-200 mb-4 space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900">Appoint to Journal Editorial Team</h4>
                        <p class="text-xs text-blue-700">Select which journal this editor will oversee.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-blue-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Select Journal *</label>
                        <select name="journal_id" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">-- Choose Journal --</option>
                            @foreach($journals as $j)
                                <option value="{{ $j->id }}" {{ old('journal_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('journal_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Editorial Role / Designation</label>
                        <select name="editorial_role" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-white">
                            @foreach($editorialRoles as $erole)
                                <option value="{{ $erole }}" {{ old('editorial_role') == $erole ? 'selected' : '' }}>{{ $erole }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Bio / Scholarly Profile (Optional)</label>
                        <input type="text" name="editorial_bio" value="{{ old('editorial_bio') }}" 
                               class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Specialization, research domain, or profile summary...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Sort Order</label>
                        <input type="number" name="editorial_sort_order" value="{{ old('editorial_sort_order', 0) }}" min="0" 
                               class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="0 = top">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="affiliation" class="block text-sm font-medium text-gray-700">Affiliation / Institution (Optional)</label>
                <input type="text" name="affiliation" id="affiliation" value="{{ old('affiliation') }}"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="e.g. Department of Computer Science, Stanford University">
                @error('affiliation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-300">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create
                    User</button>
            </div>
        </form>
    </div>
@endsection