let mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.disableNotifications();
mix.version();

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
        `modules/Admin/resources/assets/plugins/jquery/jquery.min.js`,
        `modules/Admin/resources/assets/plugins/jquery-ui/jquery-ui.min.js`,
        `modules/Admin/resources/assets/plugins/bootstrap/js/bootstrap.bundle.min.js`,
        `modules/Admin/resources/assets/plugins/datatables/jquery.dataTables.min.js`,
        `modules/Admin/resources/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js`,
        `modules/Admin/resources/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js`,
        `modules/Admin/resources/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/dataTables.buttons.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.html5.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.print.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.colVis.min.js`,
        `modules/Admin/resources/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js`,
        `modules/Admin/resources/assets/plugins/select2/js/select2.full.min.js`,
        `modules/Admin/resources/assets/plugins/jquery-validation/jquery.validate.min.js`,
        `modules/Admin/resources/assets/plugins/jquery-validation/additional-methods.min.js`,
        `modules/Admin/resources/assets/plugins/sweetalert2/sweetalert2.min.js`,
        `modules/Admin/resources/assets/plugins/toastr/toastr.min.js`,
        `modules/Admin/resources/assets/plugins/lazysizes/js/lazysizes.min.js`,
        `modules/Admin/resources/assets/plugins/nestable2/jquery.nestable.min.js`,
        `modules/Admin/resources/assets/file-manager/js/cropper.min.js`,
        `modules/Admin/resources/assets/file-manager/js/dropzone.js`,
    ],
    'public/assets/js/vendor.min.js'
).mergeManifest();

mix.combine(
    [
        `modules/Admin/resources/assets/plugins/datatables/jquery.dataTables.min.js`,
        `modules/Admin/resources/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js`,
        `modules/Admin/resources/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js`,
        `modules/Admin/resources/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/dataTables.buttons.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.html5.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.print.min.js`,
        // `assets/plugins/datatables-buttons/js/buttons.colVis.min.js`,
        `modules/Admin/resources/assets/admin/js/datatable-helper.js`,
    ],
    'public/assets/js/datatables.min.js'
).mergeManifest();

mix.styles(
    [
        `modules/Admin/resources/assets/plugins/fontawesome/css/all.min.css`,
        `modules/Admin/resources/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css`,
        `modules/Admin/resources/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css`,
        `modules/Admin/resources/assets/plugins/jquery-ui/jquery-ui.min.css`,
        // `modules/Admin/resources/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css`,
        `modules/Admin/resources/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css`,
        `modules/Admin/resources/assets/plugins/select2/css/select2.min.css`,
        `modules/Admin/resources/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css`,
        `modules/Admin/resources/assets/plugins/sweetalert2/sweetalert2.min.css`,
        `modules/Admin/resources/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css`,
        `modules/Admin/resources/assets/plugins/toastr/toastr.min.css`,
        `modules/Admin/resources/assets/plugins/nestable2/jquery.nestable.min.css`,
        `modules/Admin/resources/assets/file-manager/css/cropper.min.css`,
        `modules/Admin/resources/assets/file-manager/css/dropzone.css`,
    ],
    'public/assets/css/vendor.min.css'
).mergeManifest();

mix.combine(
    [
        `modules/Admin/resources/assets/frontend/js/online.js`,
        `modules/Admin/resources/assets/frontend/js/firebase.js`,
    ],
    `public/assets/js/init.min.js`
)
    .mergeManifest();

mix.combine(
    [

        `modules/Admin/resources/assets/admin/dist/js/admin.min.js`,
        `modules/Admin/resources/assets/admin/js/helpers.js`,
        `modules/Admin/resources/assets/admin/js/common.js`,
        `modules/Admin/resources/assets/admin/js/datatable-helper.js`,
        `modules/Admin/resources/assets/admin/js/filemanager.js`,
        `modules/Admin/resources/assets/admin/js/form-ajax.js`,
        'modules/Admin/resources/assets/admin/js/load-select2.js',
        'modules/Admin/resources/assets/admin/js/media.js',
        'modules/Admin/resources/assets/admin/js/media-modal.js',
        'modules/Admin/resources/assets/admin/js/menu.js',
        'modules/Admin/resources/assets/admin/js/widget.js',
    ],
    `public/assets/js/admin.min.js`
).mergeManifest();

mix.styles(
    [
        `modules/Admin/resources/assets/admin/dist/css/admin.css`,
        'modules/Admin/resources/assets/admin/css/media.css',
        'modules/Admin/resources/assets/admin/css/menu.css',
        'modules/Admin/resources/assets/admin/css/widget.css',
        `modules/Admin/resources/assets/admin/css/page.css`,
        `modules/Admin/resources/assets/admin/css/pricing.css`,
        `modules/Admin/resources/assets/admin/css/custom.css`,
        `modules/Admin/resources/assets/admin/css/media-modal.css`,
        `modules/Admin/resources/assets/admin/css/modern-theme.css`,
    ],
    `public/assets/css/admin.min.css`
).mergeManifest();
