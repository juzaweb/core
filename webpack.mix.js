let mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.disableNotifications();
mix.version();
mix.setPublicPath('assets/public');

mix.options(
    {
        postCss: [
            require('postcss-discard-comments')(
                {
                    removeAll: true
                }
            )
        ],
        uglify: {
            comments: false
        }
    }
);

mix.combine(
    [
        `assets/plugins/jquery/jquery.min.js`,
        `assets/plugins/bootstrap/js/bootstrap.bundle.min.js`,
        `assets/plugins/datatables/jquery.dataTables.min.js`,
        `assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js`,
        `assets/plugins/datatables-responsive/js/dataTables.responsive.min.js`,
        `assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/dataTables.buttons.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.html5.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.print.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.colVis.min.js`,
        `assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js`,
        `assets/plugins/select2/js/select2.full.min.js`,
        `assets/plugins/jquery-validation/jquery.validate.min.js`,
        `assets/plugins/jquery-validation/additional-methods.min.js`,
        `assets/plugins/sweetalert2/sweetalert2.min.js`,
        `assets/plugins/toastr/toastr.min.js`,
    ],
    'assets/public/js/vendor.min.js'
).mergeManifest();

mix.combine(
    [
        `assets/plugins/datatables/jquery.dataTables.min.js`,
        `assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js`,
        `assets/plugins/datatables-responsive/js/dataTables.responsive.min.js`,
        `assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/dataTables.buttons.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.html5.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.print.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.colVis.min.js`,
        `assets/js/datatable-helper.js`,
    ],
    'assets/public/js/datatables.min.js'
).mergeManifest();

mix.styles(
    [
        `assets/plugins/fontawesome/css/all.min.css`,
        `assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css`,
        `assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css`,
        // `assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css`,
        `assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css`,
        `assets/plugins/select2/css/select2.min.css`,
        `assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css`,
        `assets/plugins/sweetalert2/sweetalert2.min.css`,
        `assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css`,
        `assets/plugins/toastr/toastr.min.css`,
    ],
    'assets/public/css/vendor.min.css'
);

mix.combine(
    [

        `assets/dist/js/admin.min.js`,
        `assets/js/helpers.js`,
        `assets/js/common.js`,
        `assets/js/datatable-helper.js`,
        `assets/js/filemanager.js`,
        `assets/js/form-ajax.js`,
    ],
    `assets/public/js/admin.min.js`
).mergeManifest();

mix.styles(
    [
        `assets/dist/css/admin.css`,
        `assets/css/custom.css`,
    ],
    `assets/public/css/admin.min.css`
).mergeManifest();
