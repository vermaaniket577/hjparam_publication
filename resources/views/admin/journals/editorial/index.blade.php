@extends('layouts.admin')

@section('title', 'Editorial Team & Responsibilities - ' . $journal->title)
@section('breadcrumb', 'Journals / ' . $journal->title . ' / Editorial')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: 'team', 
    createModalOpen: false, 
    editModalOpen: false,
    editMember: { id: null, name: '', role: '', affiliation: '', email: '', bio: '', sort_order: 0, photo_url: '' }
}">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold shadow-xs">
            <p class="font-bold mb-1">Please correct the following errors:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Journal Header Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    {{ $journal->topic ? $journal->topic->name : 'Academic Journal' }}
                </span>
                @if($journal->issn)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-gray-100 text-gray-700">
                        ISSN: {{ $journal->issn }}
                    </span>
                @endif
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $journal->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $journal->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <h1 class="text-2xl font-serif font-black text-gray-900 tracking-tight">
                {{ $journal->title }}
            </h1>
            <p class="text-xs text-gray-500 mt-1">
                Configure Editorial Board Team Roles, Scholars, and Journal-Specific Editorial Responsibilities.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.journals.volumes.index', $journal) }}" 
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Volumes & Issues</span>
            </a>
            <a href="{{ route('admin.journals.edit', $journal) }}" 
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Settings</span>
            </a>
            <a href="{{ route('journals.show', $journal->slug) }}" target="_blank"
               class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>View Public Page</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 flex gap-4">
        <button type="button" @click="activeTab = 'team'" 
                :class="activeTab === 'team' ? 'border-blue-600 text-blue-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-semibold'"
                class="pb-3 px-2 border-b-2 text-sm flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Editorial Team</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">{{ $members->count() }}</span>
        </button>

        <button type="button" @click="activeTab = 'responsibilities'" 
                :class="activeTab === 'responsibilities' ? 'border-blue-600 text-blue-700 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 font-semibold'"
                class="pb-3 px-2 border-b-2 text-sm flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Editorial Responsibilities</span>
            @if($journal->editorial_responsibilities)
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            @endif
        </button>
    </div>

    <!-- TAB 1: EDITORIAL TEAM -->
    <div x-show="activeTab === 'team'" class="space-y-6">
        <!-- Action Row -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Editorial Team Members</h2>
                <p class="text-xs text-gray-500">Scholars, editors, and reviewers appointed to this journal.</p>
            </div>
            <button type="button" @click="createModalOpen = true"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Member</span>
            </button>
        </div>

        @if($members->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-800">No Editorial Team Members Appointed Yet</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Click "Add Member" to appoint the Editor-in-Chief, Associate Editors, and Editorial Board.</p>
                <button type="button" @click="createModalOpen = true"
                        class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add First Member</span>
                </button>
            </div>
        @else
            <!-- Members Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($members as $member)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-xs hover:shadow-md transition p-5 flex flex-col justify-between">
                        <div class="flex items-start gap-4">
                            <!-- Avatar / Photo -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-700 to-indigo-800 text-white font-serif font-black text-xl flex items-center justify-center flex-shrink-0 shadow-xs overflow-hidden">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($member->name, 0, 1) }}
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold 
                                    @if(str_contains(strtolower($member->role), 'chief')) bg-amber-100 text-amber-900 border border-amber-300
                                    @elseif(str_contains(strtolower($member->role), 'associate')) bg-blue-100 text-blue-900 border border-blue-300
                                    @elseif(str_contains(strtolower($member->role), 'section')) bg-purple-100 text-purple-900 border border-purple-300
                                    @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                    {{ $member->role }}
                                </span>
                                <h3 class="text-sm font-bold text-gray-900 mt-1 truncate">{{ $member->name }}</h3>
                                <p class="text-xs text-gray-600 line-clamp-2 mt-0.5">{{ $member->affiliation }}</p>
                                @if($member->email)
                                    <p class="text-[11px] text-blue-600 font-mono mt-1 truncate">{{ $member->email }}</p>
                                @endif
                            </div>
                        </div>

                        @if($member->bio)
                            <p class="text-xs text-gray-500 mt-3 pt-3 border-t border-gray-100 line-clamp-2 italic">
                                "{{ $member->bio }}"
                            </p>
                        @endif

                        <div class="flex items-center justify-between pt-4 mt-3 border-t border-gray-100">
                            <span class="text-[11px] font-semibold text-gray-400">Order: {{ $member->sort_order }}</span>
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="
                                            editMember = {
                                                id: {{ $member->id }},
                                                name: '{{ addslashes($member->name) }}',
                                                role: '{{ addslashes($member->role) }}',
                                                affiliation: '{{ addslashes($member->affiliation) }}',
                                                email: '{{ addslashes($member->email ?? '') }}',
                                                bio: '{{ addslashes($member->bio ?? '') }}',
                                                sort_order: {{ $member->sort_order ?? 0 }},
                                                photo_url: '{{ $member->photo ? asset('storage/' . $member->photo) : '' }}'
                                            };
                                            editModalOpen = true;
                                        "
                                        class="px-3 py-1 bg-gray-100 hover:bg-blue-50 hover:text-blue-700 text-gray-700 text-xs font-bold rounded-lg transition">
                                    Edit
                                </button>

                                <form action="{{ route('admin.journals.editorial.destroy', [$journal, $member]) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to remove {{ addslashes($member->name) }} from the editorial board?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-gray-100 hover:bg-rose-50 hover:text-rose-700 text-gray-700 text-xs font-bold rounded-lg transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- TAB 2: EDITORIAL RESPONSIBILITIES -->
    <div x-show="activeTab === 'responsibilities'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Editorial Responsibilities & Code of Conduct</h2>
                    <p class="text-xs text-gray-500">Defines the obligations, ethical mandates, and peer review oversight responsibilities for this journal's editors.</p>
                </div>
                <button type="button" 
                        onclick="insertTemplate()" 
                        class="px-3.5 py-1.5 bg-amber-50 text-amber-800 border border-amber-300 hover:bg-amber-100 text-xs font-bold rounded-xl transition">
                    ⚡ Insert Standard Academic Template
                </button>
            </div>

            <form action="{{ route('admin.journals.editorial.responsibilities', $journal) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="editorial_responsibilities" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Responsibilities Policy Content (Plain text or HTML)
                    </label>
                    <textarea name="editorial_responsibilities" id="editorial_responsibilities" rows="16"
                              class="w-full text-sm font-mono border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-xs"
                              placeholder="Enter the duties and responsibilities of the Editorial Team for this journal...">{{ old('editorial_responsibilities', $journal->editorial_responsibilities) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Save Editorial Responsibilities
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CREATE MEMBER MODAL -->
    <div x-show="createModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition.opacity>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="createModalOpen = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h3 class="text-base font-bold text-gray-900">Add Editorial Team Member</h3>
                <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.journals.editorial.store', $journal) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Full Name *</label>
                    <input type="text" name="name" required class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Prof. Dr. John Doe, Ph.D.">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Role *</label>
                        <select name="role" required class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption }}">{{ $roleOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="0 = top priority">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Affiliation & Institution *</label>
                    <input type="text" name="affiliation" required class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Department of Physics, Harvard University, USA">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email (Optional)</label>
                    <input type="email" name="email" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="scholar@university.edu">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Bio / Research Interests</label>
                    <textarea name="bio" rows="2" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="Brief academic profile or areas of expertise..."></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Photo (Optional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700">Add Member</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MEMBER MODAL -->
    <div x-show="editModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition.opacity>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative" @click.away="editModalOpen = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h3 class="text-base font-bold text-gray-900">Edit Team Member</h3>
                <button @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form :action="'{{ url('admin/journals/' . $journal->id . '/editorial') }}/' + editMember.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Full Name *</label>
                    <input type="text" name="name" x-model="editMember.name" required class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Role *</label>
                        <select name="role" x-model="editMember.role" required class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption }}">{{ $roleOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Sort Order</label>
                        <input type="number" name="sort_order" x-model="editMember.sort_order" min="0" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Affiliation & Institution *</label>
                    <input type="text" name="affiliation" x-model="editMember.affiliation" required class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" x-model="editMember.email" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Bio / Research Interests</label>
                    <textarea name="bio" x-model="editMember.bio" rows="2" class="w-full text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Change Photo (Optional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700">Update Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function insertTemplate() {
    const template = `### Editorial Responsibilities & Governance

1. **Role of the Editor-in-Chief & Editorial Board**
   - The Editor-in-Chief maintains supreme responsibility for all academic content published in this journal.
   - Editorial board members evaluate manuscript suitability, nominate peer reviewers, and adjudicate scholarly revisions.
   - Editors ensure prompt, unbiased, and constructive review cycles within 18–24 business days.

2. **Fair Play & Editorial Independence**
   - Manuscripts are evaluated solely on intellectual merit, empirical rigour, and relevance to the journal scope, without regard to authors' race, gender, citizenship, or institutional status.
   - Editorial decisions are independent from commercial publishing considerations, advertising revenue, or corporate interests.

3. **Confidentiality & Conflict of Interest**
   - Editorial members must protect the confidentiality of all submitted manuscripts and unpublished findings.
   - Editors must recuse themselves from evaluating any paper where they possess personal, financial, or collaborative conflicts of interest with the authors or institutions.

4. **Ethical Oversight & Plagiarism Prevention**
   - All submissions are subjected to automated similarity checks via CrossCheck / Turnitin prior to peer review.
   - Editors investigate substantiated allegations of plagiarism, fabrication, unauthorized authorship, or duplicate publication in adherence to COPE (Committee on Publication Ethics) protocols.

5. **Corrections and Retractions**
   - Editors promptly publish corrections, expressions of concern, or retractions whenever significant errors or research misconduct are established post-publication.`;

    const textarea = document.getElementById('editorial_responsibilities');
    if (textarea) {
        if (!textarea.value || confirm('Do you want to populate the editor with the standard academic responsibilities template?')) {
            textarea.value = template;
        }
    }
}
</script>
@endsection
