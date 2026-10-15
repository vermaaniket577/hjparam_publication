@extends('layouts.admin')
@section('title', 'Create Blog Post')
@section('breadcrumb', 'Create Blog')

@section('content')
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.blogs.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to All Blogs
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Blog Post</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blogs.index') }}"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" form="blogForm"
                    class="px-5 py-2 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md transition hover:scale-[1.02]">
                    Save & Publish
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <p class="font-bold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="blogForm" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Main Column (2 cols) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Slug Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs space-y-5">
                    <div>
                        <label for="title" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Post Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            placeholder="e.g. Advancing Open Access in Developing Research Communities"
                            class="w-full text-lg font-semibold px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="slug" class="block text-xs font-bold text-gray-500 uppercase tracking-wider">
                                URL Slug
                            </label>
                            <button type="button" onclick="generateSlug()" class="text-xs text-blue-600 hover:underline">
                                Auto-generate from title
                            </button>
                        </div>
                        <div class="flex items-center rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-3">
                            <span class="text-xs text-gray-400 select-none">/blogs/</span>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                                placeholder="advancing-open-access"
                                class="w-full text-xs font-mono py-2.5 px-1 bg-transparent border-none focus:ring-0 focus:outline-none text-gray-700 dark:text-gray-200">
                        </div>
                    </div>

                    <div>
                        <label for="summary" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Short Summary / Excerpt
                        </label>
                        <textarea id="summary" name="summary" rows="3"
                            placeholder="A concise synopsis of the article. Used in blog cards, social share previews, and meta description."
                            class="w-full text-sm px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('summary') }}</textarea>
                    </div>
                </div>

                <!-- Rich Content Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs" x-data="{ mode: 'write' }">
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3 mb-4">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Article Content (HTML / Text) <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center bg-gray-100 dark:bg-gray-700 p-1 rounded-xl text-xs font-semibold">
                            <button type="button" @click="mode = 'write'"
                                :class="mode === 'write' ? 'bg-white dark:bg-gray-800 shadow-xs text-blue-600' : 'text-gray-500'"
                                class="px-3 py-1 rounded-lg transition">
                                Editor
                            </button>
                            <button type="button" @click="mode = 'preview'; updatePreview();"
                                :class="mode === 'preview' ? 'bg-white dark:bg-gray-800 shadow-xs text-blue-600' : 'text-gray-500'"
                                class="px-3 py-1 rounded-lg transition">
                                Live Preview
                            </button>
                        </div>
                    </div>

                    <!-- Toolbar Helpers -->
                    <div x-show="mode === 'write'" class="flex flex-wrap items-center gap-1.5 p-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl mb-3 text-xs">
                        <button type="button" onclick="wrapText('<strong>', '</strong>')" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100 font-bold" title="Bold">B</button>
                        <button type="button" onclick="wrapText('<em>', '</em>')" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100 italic" title="Italic">I</button>
                        <div class="h-4 w-px bg-gray-300 mx-1"></div>
                        <button type="button" onclick="wrapText('<h2>', '</h2>')" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100 font-bold" title="Heading 2">H2</button>
                        <button type="button" onclick="wrapText('<h3>', '</h3>')" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100 font-bold" title="Heading 3">H3</button>
                        <button type="button" onclick="wrapText('<p>', '</p>')" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100" title="Paragraph">&para;</button>
                        <div class="h-4 w-px bg-gray-300 mx-1"></div>
                        <button type="button" onclick="wrapText('<blockquote>', '</blockquote>')" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100" title="Quote">&ldquo; Quote</button>
                        <button type="button" onclick="insertList()" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100" title="Bulleted List">&bull; List</button>
                        <button type="button" onclick="insertLink()" class="px-2 py-1 bg-white dark:bg-gray-800 rounded border hover:bg-gray-100" title="Link">&infin; Link</button>
                    </div>

                    <!-- Content Textarea -->
                    <div x-show="mode === 'write'">
                        <textarea id="content" name="content" rows="18" required
                            placeholder="Write your article here. Full HTML tags such as &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;blockquote&gt;, &lt;img&gt; are supported."
                            class="w-full font-mono text-sm px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('content') }}</textarea>
                    </div>

                    <!-- Live Preview Box -->
                    <div x-show="mode === 'preview'" class="p-6 bg-slate-50 dark:bg-gray-900 rounded-xl min-h-[400px] border border-gray-200 dark:border-gray-700">
                        <div id="previewContent" class="prose prose-slate max-w-none text-slate-800 dark:text-gray-200">
                            <!-- Preview injected here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column (1 col) -->
            <div class="space-y-6">
                <!-- Publish Settings Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs space-y-5">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2">
                        Publishing Status
                    </h3>

                    <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div>
                            <span class="block text-xs font-bold text-gray-800 dark:text-white">Publish Status</span>
                            <span class="text-[11px] text-gray-500">Visible on public blog page</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', '1') ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div>
                        <label for="published_at" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Publish Date & Time
                        </label>
                        <input type="datetime-local" id="published_at" name="published_at"
                            value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}"
                            class="w-full text-xs px-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <p class="text-[11px] text-gray-400 mt-1">Leave as now to publish immediately.</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition hover:scale-[1.01]">
                            Save Blog Post
                        </button>
                    </div>
                </div>

                <!-- Featured / Cover Image Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs space-y-4"
                     x-data="{ tab: 'file', previewUrl: '' }">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2">
                        Cover Image
                    </h3>

                    <!-- Image Preview Area -->
                    <div class="relative w-full h-44 rounded-xl overflow-hidden bg-slate-100 border-2 border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center group">
                        <img id="imagePreview" src="" alt="Preview"
                             class="w-full h-full object-cover hidden">
                        <div id="imagePlaceholder" class="text-center p-4">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-xs text-gray-500 font-medium">Select or upload a cover image</span>
                        </div>
                    </div>

                    <!-- Tabs: Upload vs URL -->
                    <div class="flex items-center gap-2 border-b border-gray-100 pb-2 text-xs">
                        <button type="button" @click="tab = 'file'" :class="tab === 'file' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-400'" class="pb-1 transition">
                            Upload File
                        </button>
                        <button type="button" @click="tab = 'url'" :class="tab === 'url' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-400'" class="pb-1 transition">
                            Image URL
                        </button>
                    </div>

                    <!-- File input -->
                    <div x-show="tab === 'file'">
                        <input type="file" id="featured_image_file" name="featured_image_file" accept="image/*"
                            onchange="previewUploadedImage(this)"
                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-[11px] text-gray-400 mt-1">Recommended: 1200x630 or 16:9 ratio. JPG, PNG, WEBP.</p>
                    </div>

                    <!-- URL input -->
                    <div x-show="tab === 'url'">
                        <input type="url" id="featured_image_url" name="featured_image_url" value="{{ old('featured_image_url') }}"
                            oninput="previewUrlImage(this.value)"
                            placeholder="https://images.unsplash.com/..."
                            class="w-full text-xs px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Category & Tags Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2">
                        Categorization
                    </h3>

                    <div>
                        <label for="category" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            Category
                        </label>
                        <input type="text" id="category" name="category" value="{{ old('category') }}"
                            list="categoriesList"
                            placeholder="e.g. Academic Publishing"
                            class="w-full text-xs px-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <datalist id="categoriesList">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}"></option>
                            @endforeach
                        </datalist>

                        <!-- Quick suggestion tags -->
                        <div class="flex flex-wrap gap-1.5 mt-2.5">
                            @foreach($categories as $cat)
                                <button type="button" onclick="document.getElementById('category').value = '{{ $cat }}'"
                                    class="text-[10px] bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 px-2 py-1 rounded-md transition">
                                    + {{ $cat }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="tags" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            Tags (comma separated)
                        </label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                            placeholder="open access, peer review, ethics, science"
                            class="w-full text-xs px-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Author Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2">
                        Author Details
                    </h3>

                    <div>
                        <label for="author_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            Assign User
                        </label>
                        <select id="author_id" name="author_id"
                            class="w-full text-xs px-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Current User ({{ auth()->user()->name }})</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }} ({{ ucfirst($author->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="author_name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                            Or Custom Display Name
                        </label>
                        <input type="text" id="author_name" name="author_name" value="{{ old('author_name') }}"
                            placeholder="e.g. Dr. Jane Smith or Editorial Board"
                            class="w-full text-xs px-3 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function generateSlug() {
            const title = document.getElementById('title').value;
            const slug = title.toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        }

        document.getElementById('title').addEventListener('input', function() {
            const slugInput = document.getElementById('slug');
            if (!slugInput.dataset.touched) {
                generateSlug();
            }
        });

        document.getElementById('slug').addEventListener('input', function() {
            this.dataset.touched = 'true';
        });

        function wrapText(openTag, closeTag) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            const replacement = openTag + (selectedText || 'Text') + closeTag;
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + openTag.length, start + replacement.length - closeTag.length);
        }

        function insertList() {
            const listHtml = "<ul>\n  <li>First point</li>\n  <li>Second point</li>\n  <li>Third point</li>\n</ul>";
            insertAtCursor(listHtml);
        }

        function insertLink() {
            const url = prompt("Enter URL:", "https://");
            if (url) {
                wrapText('<a href="' + url + '" class="text-blue-600 hover:underline">', '</a>');
            }
        }

        function insertAtCursor(myValue) {
            const myField = document.getElementById('content');
            if (myField.selectionStart || myField.selectionStart == '0') {
                var startPos = myField.selectionStart;
                var endPos = myField.selectionEnd;
                myField.value = myField.value.substring(0, startPos)
                    + myValue
                    + myField.value.substring(endPos, myField.value.length);
                myField.focus();
                myField.selectionStart = startPos + myValue.length;
                myField.selectionEnd = startPos + myValue.length;
            } else {
                myField.value += myValue;
                myField.focus();
            }
        }

        function updatePreview() {
            const content = document.getElementById('content').value;
            document.getElementById('previewContent').innerHTML = content || '<p class="text-gray-400 italic">No content written yet.</p>';
        }

        function previewUploadedImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('imagePreview');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    document.getElementById('imagePlaceholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewUrlImage(url) {
            const img = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');
            if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
                img.src = url;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else if (!url) {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
@endsection
