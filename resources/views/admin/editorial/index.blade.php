@extends('layouts.admin')

@section('title', 'Editorial Board Management')
@section('breadcrumb', 'Editorial Board')

@section('content')
<div class="space-y-6" x-data="{ 
    addModalOpen: false, 
    settingsModalOpen: false, 
    editModalOpen: false,
    allRoles: {{ json_encode($roles) }},
    allJournals: [
        { id: 'global', title: 'Publisher-wide / Central Board', is_central: true },
        @foreach($journals as $j)
            { id: '{{ $j->id }}', title: {!! json_encode($j->title) !!}, is_central: false },
        @endforeach
    ],

    // Add Member State
    addJournalAssignments: {
        'global': ['Editor-in-Chief']
    },
    addJournalSearch: '',
    addCustomRoleInputs: {},

    // Edit Member State
    editJournalAssignments: {},
    editJournalSearch: '',
    editCustomRoleInputs: {},
    editMember: {
        id: '',
        name: '',
        journal_id: '',
        affiliation: '',
        email: '',
        role: '',
        bio: '',
        sort_order: 0,
        photo_url: '',
        editor_uid: ''
    },

    openEdit(member, photoUrl) {
        this.editMember = {
            id: member.id,
            name: member.name || '',
            journal_id: member.journal_id || '',
            affiliation: member.affiliation || '',
            email: member.email || '',
            role: member.role || 'Editorial Board Member',
            bio: member.bio || '',
            sort_order: member.sort_order || 0,
            photo_url: photoUrl || '',
            editor_uid: member.editor_uid || ''
        };

        let map = {};
        if (member.journal_roles_map && typeof member.journal_roles_map === 'object' && Object.keys(member.journal_roles_map).length > 0) {
            map = JSON.parse(JSON.stringify(member.journal_roles_map));
        } else {
            let key = member.journal_id ? String(member.journal_id) : 'global';
            let rList = (Array.isArray(member.roles_array) && member.roles_array.length > 0)
                ? [...member.roles_array]
                : (member.role ? member.role.split(',').map(s => s.trim()).filter(Boolean) : ['Editorial Board Member']);
            map[key] = rList;
        }

        // Include any custom role into allRoles list
        Object.values(map).forEach(rList => {
            if (Array.isArray(rList)) {
                rList.forEach(r => {
                    if (r && !this.allRoles.includes(r)) {
                        this.allRoles.push(r);
                    }
                });
            }
        });

        this.editJournalAssignments = map;
        this.editJournalSearch = '';
        this.editCustomRoleInputs = {};
        this.editModalOpen = true;
    },

    isJournalAssigned(assignments, jId) {
        return assignments.hasOwnProperty(String(jId));
    },

    toggleJournalAdd(jId) {
        let key = String(jId);
        if (this.addJournalAssignments.hasOwnProperty(key)) {
            delete this.addJournalAssignments[key];
        } else {
            this.addJournalAssignments[key] = ['Editorial Board Member'];
        }
        this.addJournalAssignments = { ...this.addJournalAssignments };
    },

    toggleJournalEdit(jId) {
        let key = String(jId);
        if (this.editJournalAssignments.hasOwnProperty(key)) {
            delete this.editJournalAssignments[key];
        } else {
            this.editJournalAssignments[key] = ['Editorial Board Member'];
        }
        this.editJournalAssignments = { ...this.editJournalAssignments };
    },

    isRoleChecked(assignments, jId, role) {
        let key = String(jId);
        return Array.isArray(assignments[key]) && assignments[key].includes(role);
    },

    toggleRoleAdd(jId, role) {
        let key = String(jId);
        if (!this.addJournalAssignments[key]) {
            this.addJournalAssignments[key] = [];
        }
        if (this.addJournalAssignments[key].includes(role)) {
            this.addJournalAssignments[key] = this.addJournalAssignments[key].filter(r => r !== role);
        } else {
            this.addJournalAssignments[key].push(role);
        }
        this.addJournalAssignments = { ...this.addJournalAssignments };
    },

    toggleRoleEdit(jId, role) {
        let key = String(jId);
        if (!this.editJournalAssignments[key]) {
            this.editJournalAssignments[key] = [];
        }
        if (this.editJournalAssignments[key].includes(role)) {
            this.editJournalAssignments[key] = this.editJournalAssignments[key].filter(r => r !== role);
        } else {
            this.editJournalAssignments[key].push(role);
        }
        this.editJournalAssignments = { ...this.editJournalAssignments };
    },

    selectAllRolesAdd(jId) {
        this.addJournalAssignments[String(jId)] = [...this.allRoles];
        this.addJournalAssignments = { ...this.addJournalAssignments };
    },

    clearRolesAdd(jId) {
        this.addJournalAssignments[String(jId)] = [];
        this.addJournalAssignments = { ...this.addJournalAssignments };
    },

    selectAllRolesEdit(jId) {
        this.editJournalAssignments[String(jId)] = [...this.allRoles];
        this.editJournalAssignments = { ...this.editJournalAssignments };
    },

    clearRolesEdit(jId) {
        this.editJournalAssignments[String(jId)] = [];
        this.editJournalAssignments = { ...this.editJournalAssignments };
    },

    addCustomRoleToAdd(jId, roleName) {
        roleName = (roleName || '').trim();
        if (!roleName) return;
        if (!this.allRoles.includes(roleName)) {
            this.allRoles.push(roleName);
        }
        let key = String(jId);
        if (!this.addJournalAssignments[key]) {
            this.addJournalAssignments[key] = [];
        }
        if (!this.addJournalAssignments[key].includes(roleName)) {
            this.addJournalAssignments[key].push(roleName);
        }
        this.addJournalAssignments = { ...this.addJournalAssignments };
    },

    addCustomRoleToEdit(jId, roleName) {
        roleName = (roleName || '').trim();
        if (!roleName) return;
        if (!this.allRoles.includes(roleName)) {
            this.allRoles.push(roleName);
        }
        let key = String(jId);
        if (!this.editJournalAssignments[key]) {
            this.editJournalAssignments[key] = [];
        }
        if (!this.editJournalAssignments[key].includes(roleName)) {
            this.editJournalAssignments[key].push(roleName);
        }
        this.editJournalAssignments = { ...this.editJournalAssignments };
    },

    selectAllJournalsAdd() {
        let firstRoles = Object.values(this.addJournalAssignments)[0] || ['Editorial Board Member'];
        let updated = {};
        this.allJournals.forEach(j => {
            updated[j.id] = [...(this.addJournalAssignments[j.id] || firstRoles)];
        });
        this.addJournalAssignments = updated;
    },

    clearAllJournalsAdd() {
        this.addJournalAssignments = {};
    },

    copyRolesToAllAdd() {
        let firstKey = Object.keys(this.addJournalAssignments)[0];
        if (!firstKey) return;
        let baseRoles = [...(this.addJournalAssignments[firstKey] || ['Editorial Board Member'])];
        Object.keys(this.addJournalAssignments).forEach(k => {
            this.addJournalAssignments[k] = [...baseRoles];
        });
        this.addJournalAssignments = { ...this.addJournalAssignments };
    },

    selectAllJournalsEdit() {
        let firstRoles = Object.values(this.editJournalAssignments)[0] || ['Editorial Board Member'];
        let updated = {};
        this.allJournals.forEach(j => {
            updated[j.id] = [...(this.editJournalAssignments[j.id] || firstRoles)];
        });
        this.editJournalAssignments = updated;
    },

    clearAllJournalsEdit() {
        this.editJournalAssignments = {};
    },

    copyRolesToAllEdit() {
        let firstKey = Object.keys(this.editJournalAssignments)[0];
        if (!firstKey) return;
        let baseRoles = [...(this.editJournalAssignments[firstKey] || ['Editorial Board Member'])];
        Object.keys(this.editJournalAssignments).forEach(k => {
            this.editJournalAssignments[k] = [...baseRoles];
        });
        this.editJournalAssignments = { ...this.editJournalAssignments };
    }
}">

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </span>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editorial Board Management</h1>
            </div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage global & journal-specific editorial board members, appointments, and the public page display.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('about.page', 'editorial-board') }}" target="_blank"
                class="inline-flex items-center px-3.5 py-2 text-sm font-semibold rounded-xl text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Public Page
            </a>

            <button @click="settingsModalOpen = true"
                class="inline-flex items-center px-3.5 py-2 text-sm font-semibold rounded-xl text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Page Content Settings
            </button>

            <button @click="addModalOpen = true"
                class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Board Member
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Members</div>
            <div class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Across all journals</div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-amber-100 dark:border-amber-900/30 shadow-sm">
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Editors-in-Chief</div>
            <div class="mt-2 text-3xl font-extrabold text-amber-600">{{ $stats['chief'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Leading publications</div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-blue-100 dark:border-blue-900/30 shadow-sm">
            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Section Editors</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-600">{{ $stats['section'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Domain specialists</div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm">
            <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Board & Advisory</div>
            <div class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $stats['other'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Active scholars</div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <form method="GET" action="{{ route('admin.editorial-board.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search by name, affiliation, or bio..."
                    class="w-full text-sm border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="sm:col-span-3">
                <select name="journal_id" class="w-full text-sm border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Journals & Central</option>
                    <option value="global" {{ request('journal_id') === 'global' ? 'selected' : '' }}>-- Publisher-wide / Central Board --</option>
                    @foreach($journals as $j)
                        <option value="{{ $j->id }}" {{ request('journal_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="role" class="w-full text-sm border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 py-2 px-3 text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'journal_id', 'role']))
                    <a href="{{ route('admin.editorial-board.index') }}" class="py-2 px-3 text-sm font-semibold rounded-xl text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Editorial Board Members Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50/80 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">Member</th>
                        <th class="py-3.5 px-4 font-semibold">Designation / Role</th>
                        <th class="py-3.5 px-4 font-semibold">Assigned Journal</th>
                        <th class="py-3.5 px-4 font-semibold">Affiliation & Bio</th>
                        <th class="py-3.5 px-4 font-semibold text-center">Order</th>
                        <th class="py-3.5 px-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($members as $m)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($m->photo)
                                        <img src="{{ $m->photo_url }}" alt="{{ $m->name }}" 
                                             onerror="if(!this.dataset.retry){this.dataset.retry=1;this.src='{{ asset('storage/app/public/' . ltrim($m->photo, '/')) }}';}"
                                             class="w-10 h-10 rounded-xl object-cover border border-gray-200 shadow-xs">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-slate-800 text-white font-serif font-bold text-sm flex items-center justify-center shadow-xs">
                                            {{ strtoupper(substr($m->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $m->name }}</div>
                                        @if($m->email)
                                            <div class="text-xs text-gray-400">{{ $m->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach(array_filter(array_map('trim', explode(',', $m->role))) as $r)
                                        @if(stripos($r, 'Chief') !== false)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                ★ {{ $r }}
                                            </span>
                                        @elseif(stripos($r, 'Section') !== false)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                                {{ $r }}
                                            </span>
                                        @elseif(stripos($r, 'Associate') !== false)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-800 border border-indigo-200">
                                                {{ $r }}
                                            </span>
                                        @elseif(stripos($r, 'Managing') !== false)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-800 border border-purple-200">
                                                {{ $r }}
                                            </span>
                                        @elseif(stripos($r, 'Review') !== false)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ $r }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                {{ $r }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($m->journal)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ $m->journal->title }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-800 border border-purple-200">
                                        Publisher-wide / Central
                                    </span>
                                @endif

                                @if(is_array($m->all_appointments_summary) && count($m->all_appointments_summary) > 1)
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200"
                                              title="@foreach($m->all_appointments_summary as $app){{ $app['journal_title'] }} ({{ $app['role_string'] }})&#10;@endforeach">
                                            + {{ count($m->all_appointments_summary) - 1 }} more journal(s)
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 max-w-xs">
                                <div class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $m->affiliation }}</div>
                                @if($m->bio)
                                    <div class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $m->bio }}</div>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono text-xs text-gray-500">
                                {{ $m->sort_order }}
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <button type="button" 
                                    @click="openEdit({{ json_encode($m) }}, '{{ $m->photo_url ?? '' }}')"
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-xs px-2.5 py-1 rounded-lg hover:bg-blue-50 transition mr-1">
                                    Edit
                                </button>

                                <form action="{{ route('admin.editorial-board.destroy', $m) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('Are you sure you want to remove {{ addslashes($m->name) }} from the editorial board?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs px-2.5 py-1 rounded-lg hover:bg-red-50 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                No editorial board members found. Click "Add Board Member" above to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                {{ $members->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL: Add Board Member -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="addModalOpen = false"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-gray-100 dark:border-gray-700 z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-20">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Editorial Board Member</h3>
                        <p class="text-xs text-gray-500">Appoint an editor to multiple journals with custom roles per journal.</p>
                    </div>
                    <button @click="addModalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.editorial-board.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Prof. Dr. James Sterling" 
                               class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Affiliation / Institution *</label>
                        <input type="text" name="affiliation" required placeholder="e.g. Department of Bioengineering, University of Excellence" 
                               class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- JOURNAL ASSIGNMENTS & EDITORIAL ROLES SECTION (JOURNAL-WISE WITH CHECKBOXES) -->
                    <div class="border border-blue-200 dark:border-blue-900/60 rounded-2xl p-4 bg-gradient-to-b from-blue-50/50 via-slate-50/30 to-white dark:from-gray-900/80 dark:to-gray-900">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-gray-200/80 dark:border-gray-700">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="p-1 rounded-md bg-blue-600 text-white shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </span>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                        Assigned Journals & Editorial Roles *
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    Check journals to assign, then check multiple roles specifically for each journal.
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 shadow-xs"
                                      x-text="Object.keys(addJournalAssignments).length + ' Journal(s) Selected'">
                                </span>
                            </div>
                        </div>

                        <!-- Quick Filter & Actions Bar -->
                        <div class="mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="relative flex-1">
                                <input type="text" x-model="addJournalSearch" placeholder="Filter journals..." 
                                       class="w-full text-xs py-1.5 pl-8 pr-3 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px]">
                                <button type="button" @click="selectAllJournalsAdd()" 
                                        class="px-2 py-1 font-semibold text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition">
                                    + Select All
                                </button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button type="button" @click="copyRolesToAllAdd()" 
                                        title="Apply 1st journal's roles to all assigned journals"
                                        class="px-2 py-1 font-semibold text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:hover:bg-gray-700 rounded-lg transition">
                                    Copy Roles to All
                                </button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button type="button" @click="clearAllJournalsAdd()" 
                                        class="px-2 py-1 font-semibold text-gray-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-gray-700 rounded-lg transition">
                                    Clear
                                </button>
                            </div>
                        </div>

                        <!-- Scrollable Journal List with Role Checkboxes -->
                        <div class="mt-3 max-h-64 overflow-y-auto space-y-2 pr-1">
                            <template x-for="j in allJournals" :key="j.id">
                                <div x-show="!addJournalSearch || j.title.toLowerCase().includes(addJournalSearch.toLowerCase())"
                                     class="rounded-xl border transition"
                                     :class="isJournalAssigned(addJournalAssignments, j.id) 
                                        ? 'border-blue-400/90 bg-white dark:bg-gray-900 shadow-xs ring-1 ring-blue-300/60 dark:ring-blue-700/60' 
                                        : 'border-gray-200 dark:border-gray-700 bg-white/70 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800'">
                                    
                                    <!-- Journal Row / Toggle Checkbox -->
                                    <div class="p-2.5 flex items-center justify-between gap-2 cursor-pointer select-none"
                                         @click="toggleJournalAdd(j.id)">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <input type="checkbox" 
                                                   :checked="isJournalAssigned(addJournalAssignments, j.id)"
                                                   @click.stop="toggleJournalAdd(j.id)"
                                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            <span class="text-xs font-semibold truncate"
                                                  :class="isJournalAssigned(addJournalAssignments, j.id) ? 'text-blue-900 dark:text-blue-300 font-bold' : 'text-gray-700 dark:text-gray-300'"
                                                  x-text="j.title"></span>
                                            <template x-if="j.is_central">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                                    Central
                                                </span>
                                            </template>
                                        </div>

                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <template x-if="isJournalAssigned(addJournalAssignments, j.id)">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                                      :class="(addJournalAssignments[j.id] && addJournalAssignments[j.id].length > 0) ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-100 text-amber-800 border border-amber-300'"
                                                      x-text="(addJournalAssignments[j.id] && addJournalAssignments[j.id].length > 0) ? addJournalAssignments[j.id].length + ' role(s)' : 'No role selected!'">
                                                </span>
                                            </template>
                                            <template x-if="!isJournalAssigned(addJournalAssignments, j.id)">
                                                <span class="text-[10px] text-gray-400">Click to assign</span>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Role Options (Shown when journal is checked) -->
                                    <div x-show="isJournalAssigned(addJournalAssignments, j.id)" 
                                         class="px-3 pb-3 pt-2 border-t border-blue-100 dark:border-gray-800 bg-blue-50/30 dark:bg-gray-900/40 rounded-b-xl space-y-2">
                                        
                                        <div class="flex items-center justify-between text-[11px] text-gray-500">
                                            <span class="font-bold text-gray-700 dark:text-gray-300 text-[10px] uppercase tracking-wider">
                                                Select Editorial Role(s) for this Journal:
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click.stop="selectAllRolesAdd(j.id)" 
                                                        class="text-blue-600 hover:text-blue-800 text-[10px] font-semibold hover:underline">Select All</button>
                                                <span class="text-gray-300">|</span>
                                                <button type="button" @click.stop="clearRolesAdd(j.id)" 
                                                        class="text-gray-500 hover:text-red-600 text-[10px] font-semibold hover:underline">Clear</button>
                                            </div>
                                        </div>

                                        <!-- Roles Checkbox Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                            <template x-for="r in allRoles" :key="r">
                                                <label class="flex items-center justify-between px-2 py-1 rounded-lg border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 hover:bg-blue-50/70 dark:hover:bg-gray-700 cursor-pointer transition select-none text-xs">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <input type="checkbox" 
                                                               :value="r"
                                                               :checked="isRoleChecked(addJournalAssignments, j.id, r)"
                                                               @change="toggleRoleAdd(j.id, r)"
                                                               class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                        <span class="text-[11px] font-medium text-gray-800 dark:text-gray-200 truncate" x-text="r"></span>
                                                    </div>
                                                    <template x-if="r.toLowerCase().includes('chief')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-amber-100 text-amber-800">Chief</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('section')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-blue-100 text-blue-800">Section</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('associate')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-indigo-100 text-indigo-800">Assoc</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('managing')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-purple-100 text-purple-800">Mgr</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('review')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-emerald-100 text-emerald-800">Rev</span>
                                                    </template>
                                                </label>
                                            </template>
                                        </div>

                                        <!-- Custom Role adder for this journal -->
                                        <div class="pt-1 flex items-center gap-1.5">
                                            <input type="text" 
                                                   x-model="addCustomRoleInputs[j.id]" 
                                                   @keydown.enter.prevent="if((addCustomRoleInputs[j.id] || '').trim()){ addCustomRoleToAdd(j.id, addCustomRoleInputs[j.id]); addCustomRoleInputs[j.id] = ''; }" 
                                                   placeholder="+ Add custom role for this journal..." 
                                                   class="flex-1 text-[11px] py-1 px-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg">
                                            <button type="button" 
                                                    @click="if((addCustomRoleInputs[j.id] || '').trim()){ addCustomRoleToAdd(j.id, addCustomRoleInputs[j.id]); addCustomRoleInputs[j.id] = ''; }" 
                                                    class="px-2 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-[11px] font-bold rounded-lg transition">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Form Payload Inputs -->
                        <input type="hidden" name="journal_roles_json" :value="JSON.stringify(addJournalAssignments)">
                        <template x-for="(roles, jId) in addJournalAssignments" :key="jId">
                            <div>
                                <template x-for="r in roles" :key="r">
                                    <input type="hidden" :name="'journal_roles[' + jId + '][]'" :value="r">
                                </template>
                            </div>
                        </template>

                        <p x-show="Object.keys(addJournalAssignments).length === 0" class="text-rose-500 text-xs font-semibold mt-2">
                            ⚠️ Please check at least one journal to assign this editor.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email (Optional)</label>
                            <input type="email" name="email" placeholder="editor@university.edu" 
                                   class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Sort Order</label>
                            <input type="number" name="sort_order" value="0" min="0" placeholder="0 = top"
                                   class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Bio / Research Domains (Optional)</label>
                        <textarea name="bio" rows="2" placeholder="e.g. Specializing in molecular dynamics and cellular automation. Research areas: Bioengineering, Molecular Biology."
                                  class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Profile Photo (Optional)</label>
                        <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm">Save Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Edit Board Member -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="editModalOpen = false"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-gray-100 dark:border-gray-700 z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-20">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Board Member: <span x-text="editMember.name"></span></h3>
                        <p class="text-xs text-gray-500">Configure journal appointments and assign multiple roles journal-wise.</p>
                    </div>
                    <button @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <form :action="'{{ url('admin/editorial-board') }}/' + editMember.id" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Full Name *</label>
                        <input type="text" name="name" x-model="editMember.name" required 
                               class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Affiliation / Institution *</label>
                        <input type="text" name="affiliation" x-model="editMember.affiliation" required 
                               class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- JOURNAL ASSIGNMENTS & EDITORIAL ROLES SECTION (JOURNAL-WISE WITH CHECKBOXES) -->
                    <div class="border border-blue-200 dark:border-blue-900/60 rounded-2xl p-4 bg-gradient-to-b from-blue-50/50 via-slate-50/30 to-white dark:from-gray-900/80 dark:to-gray-900">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-gray-200/80 dark:border-gray-700">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="p-1 rounded-md bg-blue-600 text-white shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </span>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                        Assigned Journals & Editorial Roles *
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    Check journals to assign, then check multiple roles specifically for each journal.
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 shadow-xs"
                                      x-text="Object.keys(editJournalAssignments).length + ' Journal(s) Selected'">
                                </span>
                            </div>
                        </div>

                        <!-- Quick Filter & Actions Bar -->
                        <div class="mt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="relative flex-1">
                                <input type="text" x-model="editJournalSearch" placeholder="Filter journals..." 
                                       class="w-full text-xs py-1.5 pl-8 pr-3 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1.5 text-[11px]">
                                <button type="button" @click="selectAllJournalsEdit()" 
                                        class="px-2 py-1 font-semibold text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition">
                                    + Select All
                                </button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button type="button" @click="copyRolesToAllEdit()" 
                                        title="Apply 1st journal's roles to all assigned journals"
                                        class="px-2 py-1 font-semibold text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:hover:bg-gray-700 rounded-lg transition">
                                    Copy Roles to All
                                </button>
                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                <button type="button" @click="clearAllJournalsEdit()" 
                                        class="px-2 py-1 font-semibold text-gray-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-gray-700 rounded-lg transition">
                                    Clear
                                </button>
                            </div>
                        </div>

                        <!-- Scrollable Journal List with Role Checkboxes -->
                        <div class="mt-3 max-h-64 overflow-y-auto space-y-2 pr-1">
                            <template x-for="j in allJournals" :key="j.id">
                                <div x-show="!editJournalSearch || j.title.toLowerCase().includes(editJournalSearch.toLowerCase())"
                                     class="rounded-xl border transition"
                                     :class="isJournalAssigned(editJournalAssignments, j.id) 
                                        ? 'border-blue-400/90 bg-white dark:bg-gray-900 shadow-xs ring-1 ring-blue-300/60 dark:ring-blue-700/60' 
                                        : 'border-gray-200 dark:border-gray-700 bg-white/70 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800'">
                                    
                                    <!-- Journal Row / Toggle Checkbox -->
                                    <div class="p-2.5 flex items-center justify-between gap-2 cursor-pointer select-none"
                                         @click="toggleJournalEdit(j.id)">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <input type="checkbox" 
                                                   :checked="isJournalAssigned(editJournalAssignments, j.id)"
                                                   @click.stop="toggleJournalEdit(j.id)"
                                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                            <span class="text-xs font-semibold truncate"
                                                  :class="isJournalAssigned(editJournalAssignments, j.id) ? 'text-blue-900 dark:text-blue-300 font-bold' : 'text-gray-700 dark:text-gray-300'"
                                                  x-text="j.title"></span>
                                            <template x-if="j.is_central">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                                    Central
                                                </span>
                                            </template>
                                        </div>

                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <template x-if="isJournalAssigned(editJournalAssignments, j.id)">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                                      :class="(editJournalAssignments[j.id] && editJournalAssignments[j.id].length > 0) ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-100 text-amber-800 border border-amber-300'"
                                                      x-text="(editJournalAssignments[j.id] && editJournalAssignments[j.id].length > 0) ? editJournalAssignments[j.id].length + ' role(s)' : 'No role selected!'">
                                                </span>
                                            </template>
                                            <template x-if="!isJournalAssigned(editJournalAssignments, j.id)">
                                                <span class="text-[10px] text-gray-400">Click to assign</span>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Role Options (Shown when journal is checked) -->
                                    <div x-show="isJournalAssigned(editJournalAssignments, j.id)" 
                                         class="px-3 pb-3 pt-2 border-t border-blue-100 dark:border-gray-800 bg-blue-50/30 dark:bg-gray-900/40 rounded-b-xl space-y-2">
                                        
                                        <div class="flex items-center justify-between text-[11px] text-gray-500">
                                            <span class="font-bold text-gray-700 dark:text-gray-300 text-[10px] uppercase tracking-wider">
                                                Select Editorial Role(s) for this Journal:
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click.stop="selectAllRolesEdit(j.id)" 
                                                        class="text-blue-600 hover:text-blue-800 text-[10px] font-semibold hover:underline">Select All</button>
                                                <span class="text-gray-300">|</span>
                                                <button type="button" @click.stop="clearRolesEdit(j.id)" 
                                                        class="text-gray-500 hover:text-red-600 text-[10px] font-semibold hover:underline">Clear</button>
                                            </div>
                                        </div>

                                        <!-- Roles Checkbox Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                            <template x-for="r in allRoles" :key="r">
                                                <label class="flex items-center justify-between px-2 py-1 rounded-lg border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 hover:bg-blue-50/70 dark:hover:bg-gray-700 cursor-pointer transition select-none text-xs">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <input type="checkbox" 
                                                               :value="r"
                                                               :checked="isRoleChecked(editJournalAssignments, j.id, r)"
                                                               @change="toggleRoleEdit(j.id, r)"
                                                               class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                                        <span class="text-[11px] font-medium text-gray-800 dark:text-gray-200 truncate" x-text="r"></span>
                                                    </div>
                                                    <template x-if="r.toLowerCase().includes('chief')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-amber-100 text-amber-800">Chief</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('section')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-blue-100 text-blue-800">Section</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('associate')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-indigo-100 text-indigo-800">Assoc</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('managing')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-purple-100 text-purple-800">Mgr</span>
                                                    </template>
                                                    <template x-if="r.toLowerCase().includes('review')">
                                                        <span class="text-[9px] font-bold px-1 rounded bg-emerald-100 text-emerald-800">Rev</span>
                                                    </template>
                                                </label>
                                            </template>
                                        </div>

                                        <!-- Custom Role adder for this journal -->
                                        <div class="pt-1 flex items-center gap-1.5">
                                            <input type="text" 
                                                   x-model="editCustomRoleInputs[j.id]" 
                                                   @keydown.enter.prevent="if((editCustomRoleInputs[j.id] || '').trim()){ addCustomRoleToEdit(j.id, editCustomRoleInputs[j.id]); editCustomRoleInputs[j.id] = ''; }" 
                                                   placeholder="+ Add custom role for this journal..." 
                                                   class="flex-1 text-[11px] py-1 px-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg">
                                            <button type="button" 
                                                    @click="if((editCustomRoleInputs[j.id] || '').trim()){ addCustomRoleToEdit(j.id, editCustomRoleInputs[j.id]); editCustomRoleInputs[j.id] = ''; }" 
                                                    class="px-2 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-[11px] font-bold rounded-lg transition">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Form Payload Inputs -->
                        <input type="hidden" name="journal_roles_json" :value="JSON.stringify(editJournalAssignments)">
                        <template x-for="(roles, jId) in editJournalAssignments" :key="jId">
                            <div>
                                <template x-for="r in roles" :key="r">
                                    <input type="hidden" :name="'journal_roles[' + jId + '][]'" :value="r">
                                </template>
                            </div>
                        </template>

                        <p x-show="Object.keys(editJournalAssignments).length === 0" class="text-rose-500 text-xs font-semibold mt-2">
                            ⚠️ Please check at least one journal to assign this editor.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Email (Optional)</label>
                            <input type="email" name="email" x-model="editMember.email" 
                                   class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Sort Order</label>
                            <input type="number" name="sort_order" x-model="editMember.sort_order" min="0" 
                                   class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Bio / Research Domains (Optional)</label>
                        <textarea name="bio" x-model="editMember.bio" rows="2"
                                  class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-xl focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Update Photo (Optional)</label>
                        <template x-if="editMember.photo_url">
                            <div class="flex items-center gap-2 mb-2">
                                <img :src="editMember.photo_url" class="w-10 h-10 rounded-lg object-cover border">
                                <span class="text-xs text-gray-500">Current photo uploaded</span>
                            </div>
                        </template>
                        <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm">Update Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: Public Page Settings -->
    <div x-show="settingsModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/60 transition-opacity" @click="settingsModalOpen = false"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100 dark:border-gray-700 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Public Editorial Board Page Settings</h3>
                    <button @click="settingsModalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.editorial-board.settings') }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Badge Tag</label>
                            <input type="text" name="badge" value="{{ $pageSettings['badge'] }}" 
                                   class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="Leadership">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Page Title *</label>
                            <input type="text" name="title" value="{{ $pageSettings['title'] }}" required 
                                   class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Introductory Subtitle</label>
                        <textarea name="subtitle" rows="3" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">{{ $pageSettings['subtitle'] }}</textarea>
                    </div>

                    <div class="pt-2 border-t border-gray-100">
                        <label class="block text-xs font-bold text-blue-900 uppercase mb-1">"Join Us" Card Heading</label>
                        <input type="text" name="join_title" value="{{ $pageSettings['join_title'] }}" 
                               class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">"Join Us" Message</label>
                        <textarea name="join_text" rows="2" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">{{ $pageSettings['join_text'] }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">"Apply to Join" Button URL</label>
                        <input type="text" name="join_url" value="{{ $pageSettings['join_url'] }}" 
                               class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" @click="settingsModalOpen = false" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
