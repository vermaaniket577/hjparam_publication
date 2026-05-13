@php
    $siteName = $settings['site_name'] ?? 'HJPARAM';
    $pageTitle = trim($__env->yieldContent('title'));
    if (empty($pageTitle)) {
        $fullTitle = 'Open Access Peer-Reviewed Journals | ' . $siteName;
    } else {
        // Ensure | HJPARAM branding
        if (strpos($pageTitle, $siteName) === false) {
            $fullTitle = $pageTitle . ' | ' . $siteName;
        } else {
            $fullTitle = $pageTitle;
        }
    }

    $defaultDescription = 'Publish and explore peer-reviewed open access research journals across science, engineering, medicine, and technology with HJPARAM.';
    $metaDescription = trim($__env->yieldContent('meta_description'));
    if (empty($metaDescription)) {
        $metaDescription = $defaultDescription;
    }
    // Limit description to ~160 chars
    $metaDescription = \Illuminate\Support\Str::limit($metaDescription, 160);

    $defaultKeywords = 'Academic Publishing, Open Access, Peer Review, Scientific Research, HJPARAM, Google Scholar';
    $metaKeywords = trim($__env->yieldContent('meta_keywords'));
    if (empty($metaKeywords)) {
        $metaKeywords = $defaultKeywords;
    }

    $ogImage = trim($__env->yieldContent('og_image'));
    if (empty($ogImage)) {
        $ogImage = asset('images/og-image-default.png');
    }
@endphp

<!-- Primary Meta Tags -->
<title>{{ $fullTitle }}</title>
<meta name="title" content="{{ $fullTitle }}">
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">

<!-- Academic / Google Scholar Tags -->
@yield('scholar_metadata')

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:locale" content="en_US">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $fullTitle }}">
<meta property="twitter:description" content="{{ $metaDescription }}">
<meta property="twitter:image" content="{{ $ogImage }}">

<!-- Robot Tags -->
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="revisit-after" content="7 days">
<link rel="canonical" href="{{ url()->current() }}">

<!-- JSON-LD Structured Data -->
@yield('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "HJPARAM",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "contactPoint": {
    "@@type": "ContactPoint",
    "telephone": "+1-XXX-XXX-XXXX",
    "contactType": "customer service",
    "email": "info@hjparam.com"
  },
  "sameAs": [
    "https://www.facebook.com/hjparam",
    "https://twitter.com/hjparam",
    "https://www.linkedin.com/company/hjparam"
  ]
}
</script>