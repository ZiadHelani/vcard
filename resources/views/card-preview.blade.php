<!DOCTYPE html>
<html lang="en">

<head>
    @php
        /** @var \App\Models\Card $card */
        $personal = $card->personalDetails;
        $pageTitle = $personal->name ?? $card->name;
        $pageDescription = \Illuminate\Support\Str::limit(strip_tags($personal->bio ?? ''), 160);
        $logo = $personal->logo ?? null;
        $cover = $personal->cover ?? null;
        $shareImage = $logo ?: $cover;
        $cardUrl = "https://ultratech.co.il/card/{$card->slug}/preview";
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ strip_tags($pageTitle ?? '') }}</title>

    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ $cardUrl }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ strip_tags($pageTitle ?? '') }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    @if ($shareImage)
        <meta property="og:image" content="{{ $shareImage }}">
    @endif
    <meta property="og:url" content="{{ $cardUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="UltraTech VCard">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ strip_tags($pageTitle ?? '') }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if ($shareImage)
        <meta name="twitter:image" content="{{ $shareImage }}">
    @endif

    {{-- Structured data --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => strip_tags($personal->name ?? $card->name ?? ''),
            'description' => strip_tags($pageDescription ?? ''),
            'telephone' => $personal->phone ?? null,
            'url' => $cardUrl,
            'image' => $shareImage,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

</head>

<body>

    <script>
        window.location.href = "http://{{ env('SANCTUM_STATEFUL_DOMAINS') }}/card/{{ $card->slug }}/preview"
    </script>

</body>

</html>
