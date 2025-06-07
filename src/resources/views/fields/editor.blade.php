<div class="form-group">
    <label for="{{ $options['id'] ?? $name }}">{{ $label }}</label>
    <textarea class="form-control"
              name="{{ $name }}"
              id="{{ $options['id'] ?? $name }}"
              rows="{{ Arr::get($options, 'rows', 5) }}"
            @foreach(Arr::except($options, ['classes', 'id', 'rows']) as $key => $value)
                {{ $key }}="{{ $value }}"
            @endforeach
    >{{ $options['value'] ?? '' }}</textarea>
</div>

<script type="text/javascript">
    tinymce.init({
        selector: '#{{ $options['id'] ?? $name }}',
        convert_urls: true,
        document_base_url: '{{ url('/storage') }}/',
        urlconverter_callback: function(url, node, on_save, name) {
            return url.replace("{{ url('/storage') }}/", '');
        },
        height: 400,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table directionality",
            "emoticons template paste textpattern"
        ],
        menu: {
            file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
            edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
            view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
            insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
            format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
            tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
            table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
        },
        toolbar: [
            {
                name: 'new', items: [ 'newdocument' ]
            },
            {
                name: 'history', items: [ 'undo', 'redo' ]
            },
            {
                name: 'styles', items: [ 'styleselect' ]
            },
            {
                name: 'formatting', items: [ 'bold', 'italic']
            },
            {
                name: 'alignment', items: [ 'alignleft', 'aligncenter', 'alignright', 'alignjustify' ]
            },
            {
                name: 'indentation', items: [ 'outdent', 'indent' ]
            },
            {
                name: 'media', items: [ 'link', 'image', 'media' ]
            },
            {
                name: 'view', items: [ 'code', 'preview', 'fullscreen' ]
            }
        ],
        file_picker_callback : function(callback, value, meta) {
            let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            let y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
            let cmsURL = '/'+ juzaweb.adminPrefix +'/media/browser?editor=' + meta.fieldname;

            if (meta.filetype === 'image') {
                cmsURL = cmsURL + "&type=image";
            } else {
                cmsURL = cmsURL + "&type=file";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url : cmsURL,
                title : 'File Manager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no",
                onMessage: (api, message) => {
                    callback(message.content);
                }
            });
        }
    });

</script>
