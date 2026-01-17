<script type="text/javascript" nonce="{{ csp_script_nonce() }}">
    const juzaweb = {
        adminPrefix: "{{ config('core.admin_prefix') }}",
        adminUrl: "{{ url(config('core.admin_prefix')) }}",
        documentBaseUrl: "{{ Storage::disk('public')->url('/') }}",
        locale: "{{ app()->getLocale() }}",
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
