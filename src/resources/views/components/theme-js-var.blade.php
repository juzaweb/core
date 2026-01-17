@php
    $website = Network::website();
    $websiteId = $website->id;
    $gaId = ($website->isMainWebsite() || $website->isDemoWebsite())
        ? config('network.google_analytics.main')
        : config('network.google_analytics.subsites');
@endphp

@if($gaId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ $gaId }}', {
            'custom_map': {
                'dimension2': 'website_id',
            }
        });

        gtag('event', 'page_view', {
            'website_id': '{{ $websiteId }}',
        });

        @if(setting('google_analytics_id'))
        gtag('config', '{{ setting('google_analytics_id') }}');
        @endif
    </script>
@endif

<script type="text/javascript" nonce="{{ csp_script_nonce() }}">
    const juzaweb = {
        websiteId: "{{ $websiteId }}",
        documentBaseUrl: "{{ Storage::disk('public')->url('/') }}",
        imageUrl: "{{ config('services.imgproxy.base_url') }}",
        viewPage: "{{ $viewPage ?? '' }}",
        lang: {
            successfully: '{{ __('core::translation.successfully') }}',
            error: '{{ __('core::translation.error') }}',
            warning: '{{ __('core::translation.warning') }}',
            confirm: '{{ __('core::translation.are_you_sure') }}',
            cancel: '{{ __('core::translation.cancel') }}',
            ok: '{{ __('core::translation.ok') }}',
            yes: '{{ __('core::translation.yes') }}',
            remove_question: '{{ __('core::translation.are_you_sure_you_want_to_remove') }}',
            please_wait: '{{ __('core::translation.please_wait') }}',
            close: '{{ __('core::translation.close') }}',
        },
    }
</script>
