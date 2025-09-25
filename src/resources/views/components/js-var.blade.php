<script type="text/javascript">
    const juzaweb = {
        adminPrefix: "{{ config('core.admin_prefix') }}",
        documentBaseUrl: "{{ url('/storage') }}/",
        lang: {
            successfully: '{{ __('Successfully !!') }}',
            error: '{{ __('Error !!') }}',
            warning: '{{ __('Warning') }}',
            confirm: '{{ __('Are you sure?') }}',
            cancel: '{{ __('Cancel') }}',
            ok: '{{ __('OK') }}',
            yes: '{{ __('Yes') }}',
            remove_question: '{{ __('Are you sure you want to remove?') }}',
            please_wait: '{{ __('Please wait...') }}',
            close: '{{ __('Close') }}',
        },
    }
</script>