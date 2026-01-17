@extends('core::layouts.admin')

@section('head')
    <style>
        #theme-list .theme-list-item .card-bottom {
            position: absolute;
            background: rgb(255 255 255 / 88%);
            width: 100%;
            bottom: 0;
            display: none;
        }

        #theme-list .theme-list-item:hover .card-bottom {
            display: block;
        }

        #theme-list .theme-list-item .height-200 {
            height: 200px;
        }
    </style>
@endsection

@section('content')
    <div class="row" id="theme-list">
        <div class="col-md-4 p-2 theme-list-item">
            @component('core::admin.theme.components.theme-item', ['theme' => $currentTheme, 'active' => true])

            @endcomponent
        </div>
    </div>
@endsection

@section('scripts')

    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        $(function () {
            let pageSize = 12;
            let offset = 0;
            let total = 0;
            let page = 1;

            function loadData() {
                let jqxhr = $.ajax({
                    type: this.method,
                    url: '{{ route('admin.themes.get-data', [$websiteId]) }}',
                    dataType: 'json',
                    cache: false,
                    async: false,
                    data: {
                        page: page,
                        limit: pageSize,
                    }
                });

                let response = jqxhr.responseJSON;
                total = response.total;

                if (response.html) {
                    $('#theme-list').append(response.html);
                }
            }

            loadData();

            $('#theme-list').on('click', '.active-theme', function () {
                let btn = $(this);
                let icon = btn.find('i').attr('class');
                let theme = btn.data('theme');

                btn.find('i').attr('class', 'fa fa-spinner fa-spin');
                btn.prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.themes.activate', [$websiteId]) }}",
                    dataType: 'json',
                    data: {
                        theme: theme
                    }
                }).done(function (response) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);

                    if (response.status === false) {
                        show_message(response.data.message);
                        return false;
                    }

                    window.location.reload();
                    return false;
                }).fail(function (response) {
                    btn.find('i').attr('class', icon);
                    btn.prop("disabled", false);
                    show_message(response);
                    return false;
                });
            });

            $(window).scroll(function () {
                if ($(window).scrollTop() === $(document).height() - $(window).height()) {
                    if (offset + pageSize < total) {
                        page = page + 1;
                        loadData();
                    }
                }
            });
        });
    </script>
@endsection
