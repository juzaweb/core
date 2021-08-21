@php
$langs = array_merge(trans('juzaweb::app', [], 'en'), trans('juzaweb::app'));
@endphp
<script type="text/javascript">
    /**
     * MYMO CMS - THE BEST LARAVEL CMS
     *
     * @package    juzawebcms/juzawebcms
     * @link       https://github.com/juzawebcms/juzawebcms
     * @license    MIT
     */
    var juzaweb = {
        adminPrefix: "{{ config('juzaweb.admin_prefix') }}",
        adminUrl: "{{ url(config('juzaweb.admin_prefix')) }}",
        lang: JSON.parse(`@json($langs)`)
    }
</script>
