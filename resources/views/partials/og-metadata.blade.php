<!-- Standard SEO Meta Tags -->
<meta name="description" content="@yield('meta_description', 'Discover peer-reviewed, high-impact open access research journals, scholarly articles, and international academic conferences across disciplines on HJPARAM.')">
<meta name="keywords" content="@yield('meta_keywords', 'Academic Publishing, Open Access Journals, Peer Reviewed Research, Scientific Conferences, Call for Papers, Scholar Articles, HJPARAM')">
<meta name="author" content="@yield('meta_author', 'HJPARAM Publication')">
<meta name="publisher" content="HJPARAM Publication">
<meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
<meta name="googlebot" content="index, follow">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="HJPARAM Publication">
<meta property="og:locale" content="en_US">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="@yield('title', 'HJPARAM | Open Access Scholarly Journals & Research')">
<meta property="og:description" content="@yield('meta_description', 'Discover peer-reviewed, high-impact open access research journals, scholarly articles, and international academic conferences across disciplines on HJPARAM.')">
<meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="@yield('title', 'HJPARAM | Open Access Scholarly Journals & Research')">
<meta name="twitter:description" content="@yield('meta_description', 'Discover peer-reviewed, high-impact open access research journals, scholarly articles, and international academic conferences across disciplines on HJPARAM.')">
<meta name="twitter:image" content="@yield('og_image', asset('images/logo.png'))">