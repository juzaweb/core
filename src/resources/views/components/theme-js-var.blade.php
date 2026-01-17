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
            successfully: '{{ __('admin::translation.successfully') }}',
            error: '{{ __('admin::translation.error') }}',
            warning: '{{ __('admin::translation.warning') }}',
            confirm: '{{ __('admin::translation.are_you_sure') }}',
            cancel: '{{ __('admin::translation.cancel') }}',
            ok: '{{ __('admin::translation.ok') }}',
            yes: '{{ __('admin::translation.yes') }}',
            remove_question: '{{ __('admin::translation.are_you_sure_you_want_to_remove') }}',
            please_wait: '{{ __('admin::translation.please_wait') }}',
            close: '{{ __('admin::translation.close') }}',
        },
    }
</script>
