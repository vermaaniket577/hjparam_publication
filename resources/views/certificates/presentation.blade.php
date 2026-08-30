<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Presentation - {{ $scholarName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Montserrat:wght@400;500;600;700;800&family=Pinyon+Script&display=swap" rel="stylesheet">
    <style>
        @media print {
            body { margin: 0; padding: 0; background: none; }
            .no-print { display: none !important; }
            .cert-container { box-shadow: none !important; border: 12px solid #064e3b !important; }
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-script { font-family: 'Pinyon Script', cursive; }
        .font-sans { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 flex flex-col items-center justify-center font-sans">

    <!-- Action Bar -->
    <div class="max-w-[1000px] w-full mb-4 flex items-center justify-between no-print px-4">
        <a href="{{ url()->previous() }}" class="text-xs font-bold text-slate-600 hover:text-emerald-700 flex items-center gap-1.5">
            ← Back
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs flex items-center gap-2 shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- CERTIFICATE CONTAINER -->
    <div class="cert-container w-[1000px] h-[700px] bg-white rounded-2xl shadow-2xl p-12 relative overflow-hidden border-[14px] border-[#064e3b] flex flex-col justify-between text-center select-none">
        
        <!-- Corner Ornaments -->
        <div class="absolute top-4 left-4 w-16 h-16 border-t-2 border-l-2 border-[#d97706]"></div>
        <div class="absolute top-4 right-4 w-16 h-16 border-t-2 border-r-2 border-[#d97706]"></div>
        <div class="absolute bottom-4 left-4 w-16 h-16 border-b-2 border-l-2 border-[#d97706]"></div>
        <div class="absolute bottom-4 right-4 w-16 h-16 border-b-2 border-r-2 border-[#d97706]"></div>

        <!-- Watermark / Background Glow -->
        <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
            <svg class="w-96 h-96 text-emerald-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.8L19.5 19h-15L12 5.8z"/></svg>
        </div>

        <!-- Certificate Header -->
        <div class="space-y-2 relative z-10">
            <div class="flex items-center justify-center gap-3">
                <span class="text-xs font-black tracking-[0.3em] uppercase text-[#d97706]">HJPARAM ACADEMIC CONFERENCES</span>
            </div>
            <h1 class="text-3xl font-cinzel font-bold text-[#064e3b] tracking-wider uppercase">
                Certificate of Oral Presentation
            </h1>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Presented for Outstanding Scientific Contribution</p>
        </div>

        <!-- Body Text -->
        <div class="space-y-3.5 my-auto relative z-10 px-8">
            <p class="text-xs text-slate-500 font-medium italic">This certificate is awarded to</p>
            
            <div class="text-4xl font-serif font-black text-[#064e3b] tracking-tight border-b-2 border-[#d97706]/40 pb-2 inline-block px-12">
                {{ $scholarName }}
            </div>

            <p class="text-xs text-slate-600 leading-relaxed max-w-3xl mx-auto pt-2 font-medium">
                in recognition and appreciation of presenting the research paper entitled:<br>
                <strong class="text-base text-slate-900 font-serif font-bold italic block mt-1">"{{ $submission->title }}"</strong>
                at the <strong class="text-[#064e3b] font-bold">{{ $submission->conference ? $submission->conference->title : 'International Academic Conference' }}</strong> organized by <strong class="text-slate-800">{{ $submission->conference ? $submission->conference->organizer_name : 'HJParam Publications' }}</strong> on <strong class="text-slate-800">{{ $submission->conference ? $submission->conference->start_date->format('F d, Y') : date('F d, Y') }}</strong>.
            </p>
        </div>

        <!-- Certificate Footer (Signatures & Verification Code) -->
        <div class="grid grid-cols-3 items-end pt-6 border-t border-slate-200 relative z-10 text-xs">
            <!-- Left: Session Chair Sign -->
            <div class="text-center space-y-1">
                <div class="font-script text-2xl text-emerald-900 leading-none">Dr. R. Sharma</div>
                <div class="w-36 h-0.5 bg-slate-300 mx-auto"></div>
                <span class="block text-[10px] font-bold uppercase text-slate-600">Technical Session Chair</span>
            </div>

            <!-- Center: Verified Seal -->
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-[#064e3b] text-[#fde047] flex flex-col items-center justify-center border-4 border-[#d97706] shadow-md">
                    <span class="text-[7px] font-black uppercase tracking-widest">VERIFIED</span>
                    <span class="text-[9px] font-black">PRESENTER</span>
                </div>
                <span class="text-[9px] font-mono text-slate-500 mt-1 font-bold">{{ $submission->certificate_presentation_code }}</span>
            </div>

            <!-- Right: Director Sign -->
            <div class="text-center space-y-1">
                <div class="font-script text-2xl text-emerald-900 leading-none">Prof. S. Vance</div>
                <div class="w-36 h-0.5 bg-slate-300 mx-auto"></div>
                <span class="block text-[10px] font-bold uppercase text-slate-600">Academic Director</span>
            </div>
        </div>
    </div>
</body>
</html>
