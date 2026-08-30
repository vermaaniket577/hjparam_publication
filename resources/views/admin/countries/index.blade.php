@extends('layouts.admin')
@section('title', 'Manage Countries')
@section('breadcrumb', 'Geographic Registry')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 animate-fade-in">
    <!-- List Column -->
    <div class="lg:col-span-7 space-y-6">
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-10 py-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest">Territory Registry</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-50/30">
                        <tr>
                            <th class="px-10 py-5">Country / Code</th>
                            <th class="px-10 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($countries as $country)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">{{ $country->code }}</div>
                                        <div class="font-bold text-slate-800">{{ $country->name }}</div>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" onsubmit="return confirm('Purge protocol initiated?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Column -->
    <div class="lg:col-span-5">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl border border-slate-100 sticky top-32">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-10 pb-4 border-b">Initialize New Territory</h2>
            
            <form action="{{ route('admin.countries.store') }}" method="POST" class="space-y-8">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Country Designation (Name)</label>
                    <input type="text" name="name" required placeholder="Ex: United Kingdom"
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700">
                    @error('name') <p class="mt-2 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Terminal Code (ISO/Short)</label>
                    <input type="text" name="code" required placeholder="Ex: UK"
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700">
                    @error('code') <p class="mt-2 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-5 bg-[#0f172a] hover:bg-blue-600 text-white font-black rounded-2xl shadow-xl transition-all duration-500 hover:-translate-y-1 uppercase tracking-widest text-[11px] flex items-center justify-center gap-3">
                    Commit to Registry
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
