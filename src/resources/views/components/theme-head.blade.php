@php
    $favicon = setting("favicon");
@endphp

<link href="//dnjs.cloudflare.com" rel="dns-prefetch"/>
<link href="//fonts.gstatic.com" rel="dns-prefetch"/>
<link href="//cdn.juzaweb.com" rel="dns-prefetch"/>
<link href="//img.juzaweb.com" rel="dns-prefetch"/>
<link href="//img2.juzaweb.com" rel="dns-prefetch"/>
<link href="//pagead2.googlesyndication.com" rel="dns-prefetch"/>
<link href="//www.googletagmanager.com" rel="dns-prefetch"/>
<link href="//www.google-analytics.com" rel="dns-prefetch"/>

<link rel="shortcut icon" href="{{ $favicon ? upload_url($favicon) : asset("favicon.ico") }}"/>
<link href="{{ url()->current() }}" rel="canonical"/>

<!-- Metadata for Open Graph protocol. See http://ogp.me/. -->
@if(isset($ogType))
    <meta content="{{ $ogType }}" property="og:type"/>
@endif

@if(isset($title))
    <meta content="{{ $title }}" property="og:title"/>
    <meta content="{{ $title }}" name="twitter:title"/>
@endif

@if(isset($description))
    <meta content="{{ $description }}" property="og:description"/>
    <meta content="{{ $description }}" name="twitter:description"/>
@endif

@if(isset($image))
    <meta content="{{ $image }}" property="og:image"/>
    <meta content="{{ $image }}" name="twitter:image"/>
    <meta content="summary_large_image" name="twitter:card"/>
@endif

<meta content="{{ url()->current() }}" property="og:url"/>
<meta content="{{ setting('sitename') ?? setting('title') }}" property="og:site_name"/>
<meta content="{{ url()->current() }}" name="twitter:domain"/>

@if(setting('custom_header_script'))
    {!! setting('custom_header_script') !!}
@endif

