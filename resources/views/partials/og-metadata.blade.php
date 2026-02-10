@php
    $siteName = $settings['site_name'] ?? 'HJPARAM Publication';
    $defaultDescription = 'Discover peer-reviewed, open access research across disciplines through our global research gateway.';
    $defaultKeywords = 'Academic Publishing, Open Access, Peer Review, Scientific Research, HJPARAM';
    $ogImage = trim($__env->yieldContent('og_image'));
    if (empty($ogImage)) {
        $ogImage = asset('images/logo.png');
    }
@endphp

<!-- Primary Meta Tags -->
<title>@yield('title', $siteName . ' | Open Access Research')</title>
<meta name="title" content="@yield('title', $siteName)">
<meta name="description" content="@yield('meta_description', $defaultDescription)">
<meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="@yield('title', $siteName)">
<meta property="og:description" content="@yield('meta_description', $defaultDescription)">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:locale" content="en_US">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="@yield('title', $siteName)">
<meta property="twitter:description" content="@yield('meta_description', $defaultDescription)">
<meta property="twitter:image" content="{{ $ogImage }}">

<!-- Robot Tags -->
<meta name="robots" content="index, follow">
<meta name="revisit-after" content="7 days">
<link rel="canonical" href="{{ url()->current() }}">