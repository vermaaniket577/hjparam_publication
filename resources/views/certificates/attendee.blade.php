<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Attendance - {{ $scholarName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Montserrat:wght@400;500;600;700;800&family=Pinyon+Script&display=swap" rel="stylesheet">
    <style>
        @media print {
            body { margin: 0; padding: 0; background: none; }
            .no-print { display: none !important; }
            .cert-container { box-shadow: none !important; border: 12px solid #1e3a8a !important; }
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-script { font-family: 'Pinyon Script', cursive; }
        .font-sans { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 flex flex-col items-center justify-center font-sans">

    <!-- Action Bar -->
    <div class="max-w-[1000px] w-full mb-4 flex items-center justify-between no-print px-4">
        <a href="{{ url()->previous() }}" class="text-xs font-bold text-slate-600 hover:text-blue-600 flex items-center gap-1.5">
            ← Back
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- CERTIFICATE CONTAINER -->
    <div class="cert-container w-[1000px] h-[700px] bg-white rounded-2xl shadow-2xl p-12 relative overflow-hidden border-[14px] border-[#0f2854] flex flex-col justify-between text-center select-none">
        
        <!-- Corner Ornaments -->
        <div class="absolute top-4 left-4 w-16 h-16 border-t-2 border-l-2 border-[#b8860b]"></div>
        <div class="absolute top-4 right-4 w-16 h-16 border-t-2 border-r-2 border-[#b8860b]"></div>
        <div class="absolute bottom-4 left-4 w-16 h-16 border-b-2 border-l-2 border-[#b8860b]"></div>
        <div class="absolute bottom-4 right-4 w-16 h-16 border-b-2 border-r-2 border-[#b8860b]"></div>

        <!-- Watermark / Background Glow -->
        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
            <svg class="w-96 h-96 text-blue-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.8L19.5 19h-15L12 5.8z"/></svg>
        </div>

        <!-- Certificate Header -->
        <div class="space-y-2 relative z-10">
            <div class="flex items-center justify-center gap-3">
                <span class="text-xs font-black tracking-[0.3em] uppercase text-[#b8860b]">HJPARAM ACADEMIC CONFERENCES</span>
            </div>
            <h1 class="text-3xl font-cinzel font-bold text-[#0f2854] tracking-wider uppercase">
                Certificate of Attendance
            </h1>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Official Verified Participation Record</p>
        </div>

        <!-- Body Text -->
        <div class="space-y-4 my-auto relative z-10 px-8">
            <p class="text-xs text-slate-500 font-medium italic">This is proudly presented to</p>
            
            <div class="text-4xl font-serif font-black text-[#0f2854] tracking-tight border-b-2 border-[#b8860b]/40 pb-2 inline-block px-12">
                {{ $scholarName }}
            </div>

            <p class="text-xs text-slate-600 leading-relaxed max-w-2xl mx-auto pt-2 font-medium">
                for active participation as a certified delegate in the <strong class="text-[#0f2854] font-bold">{{ $submission->conference ? $submission->conference->title : 'International Academic Conference' }}</strong> organized by <strong class="text-slate-800">{{ $submission->conference ? $submission->conference->organizer_name : 'HJParam Publications' }}</strong> held during <strong class="text-slate-800">{{ $submission->conference ? $submission->conference->start_date->format('F d, Y') : date('F d, Y') }}</strong>.
            </p>
        </div>

        <!-- Certificate Footer (Signatures & Verification Code) -->
        <div class="grid grid-cols-3 items-end pt-6 border-t border-slate-200 relative z-10 text-xs">
            <!-- Left: Coordinator Sign -->
            <div class="text-center space-y-1">
                <div class="font-script text-2xl text-blue-900 leading-none">Dr. R. Sharma</div>
                <div class="w-36 h-0.5 bg-slate-300 mx-auto"></div>
                <span class="block text-[10px] font-bold uppercase text-slate-600">Conference Chair</span>
            </div>

            <!-- Center: Verified Seal -->
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-[#0f2854] text-[#fde047] flex flex-col items-center justify-center border-4 border-[#b8860b] shadow-md">
                    <span class="text-[7px] font-black uppercase tracking-widest">VERIFIED</span>
                    <span class="text-[9px] font-black">CPD</span>
                </div>
                <span class="text-[9px] font-mono text-slate-500 mt-1 font-bold">{{ $submission->certificate_attendee_code }}</span>
            </div>

            <!-- Right: Director Sign -->
            <div class="text-center space-y-1">
                <div class="font-script text-2xl text-blue-900 leading-none">Prof. S. Vance</div>
                <div class="w-36 h-0.5 bg-slate-300 mx-auto"></div>
                <span class="block text-[10px] font-bold uppercase text-slate-600">Academic Director</span>
            </div>
        </div>
    </div>
</body>
</html>
