@extends('layouts.admin')
@section('title', 'Manage Conferences')
@section('breadcrumb', 'Conferences')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Conferences</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $conferences->total() }} events listed</p>
        </div>
        <a href="{{ route('admin.conferences.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Conference
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Conference Info</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Details</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($conferences as $conf)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white mb-0.5">{{ $conf->title }}</div>
                                        <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest">{{ $conf->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 font-medium text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"></path>
                                        </svg>
                                        {{ $conf->city }}, {{ $conf->country->name }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"></path>
                                        </svg>
                                        {{ $conf->start_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex flex-col gap-2">
                                    @if($conf->status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 justify-center">Approved</span>
                                    @elseif($conf->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 justify-center">Pending</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 justify-center">Rejected</span>
                                    @endif

                                    @if($conf->is_featured)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 uppercase tracking-widest justify-center">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.conferences.toggle-featured', $conf) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" title="Toggle Featured" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all">
                                            <svg class="w-5 h-5 {{ $conf->is_featured ? 'fill-amber-500 text-amber-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('admin.conferences.edit', $conf) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.conferences.destroy', $conf) }}" method="POST" onsubmit="return confirm('Silencing this event registry permanently. Confirm deletion?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 mx-auto rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="font-bold">No conferences found</p>
                                <a href="{{ route('admin.conferences.create') }}" class="text-blue-600 font-bold text-sm mt-2 block">Create your first conference</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($conferences->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                {{ $conferences->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
