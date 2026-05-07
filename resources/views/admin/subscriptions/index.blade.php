@extends('layouts.admin')
@section('title', 'Subscriptions')
@section('breadcrumb', 'Alert Subscriptions')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Alert Subscriptions</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $subscriptions->total() }} subscribers</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Email Address</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Interests</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($subscriptions as $sub)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $sub->email }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if(is_array($sub->interests))
                                    @foreach($sub->interests as $interestId)
                                        @php $topic = \App\Models\Topic::find($interestId); @endphp
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-black uppercase rounded">{{ $topic->name ?? 'Unknown' }}</span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400 italic">None selected</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.subscriptions.update', $sub) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $sub->active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $sub->active ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.subscriptions.destroy', $sub) }}" method="POST" onsubmit="return confirm('Remove subscriber?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($subscriptions->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
