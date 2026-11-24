<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Appointment - {{ $cert->recipient_name }} | {{ $cert->journal_name }}</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400;1,600&family=Poppins:wght@400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind Play CDN for immediate layout rendering -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        @media print {
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .certificate-outer-shell {
                box-shadow: none !important;
                margin: 0 auto !important;
                padding: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                min-height: 100vh !important;
                border: none !important;
            }
            .certificate-card {
                box-shadow: none !important;
                width: 100% !important;
                height: 100% !important;
                page-break-inside: avoid;
            }
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
        }

        .font-serif-title {
            font-family: 'Cinzel', 'Playfair Display', Georgia, serif;
        }

        .font-serif-body {
            font-family: 'Playfair Display', Georgia, serif;
        }

        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }

        /* Certificate Dimensions for Standard A4 Landscape */
        .certificate-sheet {
            width: 1120px;
            min-height: 792px;
            background: #ffffff;
            position: relative;
        }

        .outer-navy-border {
            border: 4px solid #0d3559;
        }

        .inner-navy-border {
            border: 1.5px solid #0d3559;
        }

        /* Gold Gradient Text */
        .text-gold-scholar {
            color: #b37712;
            text-shadow: 0 1px 1px rgba(0,0,0,0.06);
        }

        /* Editable field styling */
        [contenteditable="true"] {
            outline: 2px dashed #3b82f6;
            outline-offset: 3px;
            background-color: rgba(239, 246, 255, 0.5);
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen py-6 px-4 flex flex-col items-center justify-start">

    <!-- FLOATING TOP CONTROLS (NO PRINT) -->
    <div class="no-print w-full max-w-[1120px] mb-6 flex flex-wrap items-center justify-between gap-4 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-slate-200 z-50">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() ?: route('admin.editorial-board.index') }}" 
               class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
            <span class="text-xs font-mono font-bold text-slate-400">ID: {{ $cert->certificate_number }}</span>
            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-black uppercase tracking-wider">
                Official Appointment
            </span>
        </div>

        <div class="flex items-center gap-2">
            @auth
                @if(in_array(auth()->user()->role, ['admin', 'super_admin', 'sub_admin', 'editor']))
                    <button type="button" id="toggleEditBtn" onclick="toggleEditMode()"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span id="editBtnText">Live Edit</span>
                    </button>

                    <button type="button" id="saveChangesBtn" onclick="saveCertificateChanges()" style="display: none;"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                @endif
            @endauth

            <button type="button" onclick="copyVerificationLink()"
                    class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs uppercase tracking-wider rounded-xl border border-blue-200 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Copy Verify URL
            </button>

            <a href="{{ route('certificates.verify', $cert->certificate_number) }}" target="_blank"
               class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl border border-slate-200 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Verify Online
            </a>

            <button type="button" onclick="window.print()"
                    class="px-6 py-2 bg-gradient-to-r from-blue-700 to-indigo-800 hover:from-blue-800 hover:to-indigo-900 text-white font-extrabold text-xs uppercase tracking-widest rounded-xl transition shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Save PDF
            </button>
        </div>
    </div>

    <!-- CERTIFICATE CONTAINER (A4 LANDSCAPE) -->
    <div class="certificate-outer-shell shadow-2xl overflow-hidden rounded-sm bg-white">
        <div class="certificate-sheet p-6 flex flex-col justify-between outer-navy-border relative">
            
            <!-- WATERMARK LOGO IN BACKGROUND -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.035] overflow-hidden">
                <img src="{{ asset('images/certificates/editorial_shield_logo.png') }}" 
                     alt="" class="w-[500px] h-auto object-contain select-none">
            </div>

            <!-- INNER NAVY BORDER FRAME -->
            <div class="inner-navy-border p-6 flex-1 flex flex-col justify-between relative z-10">

                <!-- 1. TOP HEADER ROW (SHIELD LOGO + JOURNAL TITLE + SUBTITLE) -->
                <div class="flex items-center gap-6 mb-4">
                    <!-- SHIELD LOGO (LEFT) -->
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/certificates/editorial_shield_logo.png') }}" 
                             alt="HJPARAM Logo" 
                             class="h-[88px] w-auto object-contain drop-shadow-sm">
                    </div>

                    <!-- JOURNAL TITLE & SUBTITLE (CENTER / RIGHT) -->
                    <div class="flex-1 text-center pr-12">
                        <h1 id="field_journal_name"
                            class="font-serif-title font-bold text-[25px] leading-tight text-[#0c2b48] tracking-wide mb-1"
                            data-field="journal_name">
                            {{ $cert->journal_name ?: 'Aridax International Journal of Research in Science & Technology' }}
                        </h1>
                        <p class="text-[12.5px] font-semibold text-[#185d34] tracking-tight">
                            International Peer-Reviewed Science, Engineering &amp; Technology Research Academic Journal &bull; <a href="https://www.hjparam.com" target="_blank" class="hover:underline text-[#185d34]">www.hjparam.com</a>
                        </p>
                    </div>
                </div>

                <!-- 2. APPOINTMENT RIBBONS -->
                <div class="w-full mb-6 shadow-sm">
                    <!-- NAVY PRIMARY BANNER -->
                    <div class="bg-[#0b2b4d] text-white text-center py-2.5 px-4">
                        <h2 class="font-montserrat font-extrabold text-[19px] sm:text-[21px] tracking-[0.08em] uppercase m-0 leading-none">
                            @if($cert->recipient_role && str_contains(strtolower($cert->recipient_role), 'reviewer'))
                                CERTIFICATE OF REVIEWER APPOINTMENT
                            @else
                                CERTIFICATE OF EDITORIAL BOARD APPOINTMENT
                            @endif
                        </h2>
                    </div>
                    <!-- SAGE GREEN SUB-BANNER -->
                    <div class="bg-[#e2eed8] text-[#225823] text-center py-1 px-4">
                        <p class="font-montserrat font-bold text-[10.5px] tracking-[0.15em] uppercase m-0 leading-none">
                            HONORING ACADEMIC EXCELLENCE &amp; SCHOLARLY PEER-REVIEW LEADERSHIP
                        </p>
                    </div>
                </div>

                <!-- 3. CERTIFICATE BODY CONTENT -->
                <div class="text-center flex-1 flex flex-col justify-center my-auto py-1">
                    <!-- PREAMBLE -->
                    <p class="font-serif-body italic text-[#475569] text-[16.5px] mb-2 leading-none">
                        This is to certify that
                    </p>

                    <!-- RECIPIENT SCHOLAR NAME -->
                    <h3 id="field_recipient_name" 
                        class="font-serif-title font-bold text-[32px] leading-tight text-gold-scholar tracking-wide my-1.5"
                        data-field="recipient_name">
                        {{ $cert->recipient_name }}
                    </h3>

                    <!-- DESIGNATION & DEPARTMENT -->
                    <p id="field_designation_dept"
                       class="text-[15px] font-bold text-[#1e293b] leading-tight mt-1"
                       data-field="designation">
                        {{ $cert->designation ? $cert->designation . ($cert->department ? ', ' . $cert->department : '') : ($cert->department ?: 'Professor & Scholar') }}
                    </p>

                    <!-- INSTITUTION, CITY & COUNTRY -->
                    <p id="field_institution_country"
                       class="font-serif-body italic text-[14px] text-[#475569] leading-tight mt-1 mb-4"
                       data-field="institution">
                        {{ $cert->institution ? $cert->institution . ($cert->city_country ? ', ' . $cert->city_country : '') : ($cert->city_country ?: 'Academic Institute') }}
                    </p>

                    <!-- APPOINTMENT PARAGRAPH -->
                    <div class="max-w-[890px] mx-auto text-[#2b394d] text-[14px] sm:text-[14.5px] leading-[1.65] font-normal text-center mt-2 px-4">
                        has been formally appointed to serve as 
                        <strong id="field_recipient_role" class="font-bold text-[#0c2b48]" data-field="recipient_role">
                            {{ $cert->recipient_role ?: 'Editorial Board Member & Managing Editor' }}
                        </strong> 
                        on the Editorial Board of 
                        <strong class="font-bold text-[#0c2b48]">
                            {{ $cert->journal_name ?: 'Aridax International Journal of Research in Science & Technology' }} (www.hjparam.com)
                        </strong>. 
                        This appointment recognizes outstanding research contributions, domain expertise, and dedication to maintaining the highest standards of scholarly publication.
                    </div>
                </div>

                <!-- 4. BOTTOM FOOTER SECTION (VERIFICATION METADATA + OFFICIAL SIGNATURE) -->
                <div class="pt-6 mt-4 border-t border-slate-200/80 flex items-end justify-between">
                    
                    <!-- LEFT COLUMN: CERTIFICATE CREDENTIALS -->
                    <div class="text-left space-y-1 text-[#0c2b48] text-[12px] leading-tight">
                        <div class="font-medium">
                            <span class="text-slate-500 font-semibold">Certificate ID:</span> 
                            <strong id="field_certificate_number" class="font-bold text-[#0c2b48] ml-1 font-mono tracking-wider" data-field="certificate_number">
                                {{ $cert->certificate_number }}
                            </strong>
                        </div>
                        <div class="font-medium">
                            <span class="text-slate-500 font-semibold">Date of Issue:</span> 
                            <strong id="field_issue_date" class="font-bold text-[#0c2b48] ml-1" data-field="issue_date">
                                {{ $cert->issue_date ? $cert->issue_date->format('d.m.Y') : now()->format('d.m.Y') }}
                            </strong>
                        </div>
                        <div class="font-medium">
                            <span class="text-slate-500 font-semibold">Official Portal:</span> 
                            <a href="https://www.hjparam.com" target="_blank" class="text-[#0c2b48] ml-1 italic hover:underline">
                                www.hjparam.com
                            </a>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: OFFICIAL AUTHORIZED SIGNATURE -->
                    <div class="text-center min-w-[280px]">
                        <!-- SIGNATURE IMAGE -->
                        <div class="h-[58px] flex items-end justify-center mb-1">
                            <img src="{{ asset('images/certificates/director_signature.png') }}" 
                                 alt="Signature of Dr. Ayushi Mishra" 
                                 class="h-[52px] w-auto object-contain mx-auto">
                        </div>
                        
                        <!-- SIGNATURE UNDERLINE -->
                        <div class="w-[260px] h-[1px] bg-slate-400 mx-auto mb-1.5"></div>
                        
                        <!-- SIGNATORY DETAILS -->
                        <p class="font-bold text-[#0c2b48] text-[14px] leading-tight m-0">
                            Dr. Ayushi Mishra
                        </p>
                        <p class="text-[12px] font-medium text-[#475569] leading-tight m-0 mt-0.5">
                            Managing Director
                        </p>
                        <p class="text-[11px] font-medium text-[#64748b] leading-tight m-0 mt-0.5">
                            HJPARAM Intellectual Services &amp; Technology
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- NOTIFICATION TOAST -->
    <div id="toastNotification" class="no-print fixed bottom-6 right-6 px-5 py-3 rounded-xl bg-slate-900 text-white font-bold text-xs shadow-2xl border border-slate-700 hidden z-50">
        Changes Saved Successfully!
    </div>

    <script>
        let isEditing = false;

        function toggleEditMode() {
            isEditing = !isEditing;
            const fields = [
                'field_recipient_name',
                'field_designation_dept',
                'field_institution_country',
                'field_recipient_role',
                'field_journal_name',
                'field_certificate_number',
                'field_issue_date'
            ];

            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.contentEditable = isEditing ? "true" : "false";
                }
            });

            document.getElementById('editBtnText').innerText = isEditing ? 'Exit Edit' : 'Live Edit';
            document.getElementById('saveChangesBtn').style.display = isEditing ? 'inline-flex' : 'none';

            if (isEditing) {
                showToast('Edit Mode Active: Click text directly to customize, then click Save Changes.');
            }
        }

        async function saveCertificateChanges() {
            const data = {
                _token: '{{ csrf_token() }}',
                recipient_name: document.getElementById('field_recipient_name')?.innerText.trim(),
                recipient_role: document.getElementById('field_recipient_role')?.innerText.trim(),
                journal_name: document.getElementById('field_journal_name')?.innerText.trim(),
                certificate_number: document.getElementById('field_certificate_number')?.innerText.trim(),
                designation: document.getElementById('field_designation_dept')?.innerText.trim(),
                institution: document.getElementById('field_institution_country')?.innerText.trim()
            };

            try {
                const response = await fetch('{{ route('certificates.update', $cert) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const res = await response.json();
                if (res.success || response.ok) {
                    showToast('Certificate updated and synchronized successfully!');
                    toggleEditMode();
                } else {
                    showToast(res.message || 'Error saving changes.');
                }
            } catch (err) {
                console.error(err);
                showToast('Saved locally. Refresh to verify server sync.');
                toggleEditMode();
            }
        }

        function copyVerificationLink() {
            const url = '{{ route('certificates.verify', $cert->certificate_number) }}';
            navigator.clipboard.writeText(url).then(() => {
                showToast('Verification URL copied to clipboard!');
            }).catch(() => {
                prompt('Copy this link:', url);
            });
        }

        function showToast(msg) {
            const toast = document.getElementById('toastNotification');
            if (toast) {
                toast.innerText = msg;
                toast.classList.remove('hidden');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3500);
            }
        }
    </script>
</body>
</html>
