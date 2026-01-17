@php
    $website = Network::website();
    $websiteId = $website->id;
    $gaId = config('network.google_analytics.main');
@endphp

@if ($gaId && !$website->isMainWebsite())
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ $gaId }}');
    </script>
@endif

<script type="text/javascript" nonce="{{ csp_script_nonce() }}">
    const juzaweb = {
        websiteId: "{{ $websiteId }}",
        adminPrefix: "{{ config('core.admin_prefix') }}/{{ $websiteId }}",
        adminUrl: "{{ url(config('core.admin_prefix') . '/' . $websiteId) }}",
        documentBaseUrl: "{{ Storage::disk('public')->url('/') }}",
        staticBaseUrl: "{{ Storage::disk('cloud')->url('/') }}",
        imageUrl: "{{ config('services.imgproxy.base_url') }}",
        locale: "{{ app()->getLocale() }}",
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
