@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('breadcrumb', 'Contact Intelligence')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Intelligence</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $messages->total() }} inquiries received</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Inquirer Identity</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Subject Vector</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Read Status</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($messages as $msg)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ !$msg->read_at ? 'bg-blue-50/50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $msg->name }}</div>
                            <div class="text-[10px] text-gray-400 font-medium">{{ $msg->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $msg->subject }}</td>
                        <td class="px-6 py-4">
                            @if($msg->read_at)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-black uppercase rounded">Neutralized</span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-black uppercase rounded animate-pulse">Active Inquiry</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.contact-messages.show', $msg) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2.5"></path>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2.5"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.contact-messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Purge message?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($messages->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
