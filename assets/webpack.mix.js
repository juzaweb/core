let mix = require('laravel-mix');
let path = require('path');
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

const assetPath = path.dirname(__filename, '');;
const publicPath = `vendor/juzaweb/assets/public`;

mix.setPublicPath(publicPath);

mix.combine(
    [
        `${assetPath}/plugins/jquery/jquery.min.js`,
        `${assetPath}/plugins/jquery-ui/jquery-ui.min.js`,
        `${assetPath}/plugins/bootstrap/js/bootstrap.bundle.min.js`,
        `${assetPath}/plugins/datatables/jquery.dataTables.min.js`,
        `${assetPath}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js`,
        `${assetPath}/plugins/datatables-responsive/js/dataTables.responsive.min.js`,
        `${assetPath}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js`,
        `${assetPath}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js`,
        `${assetPath}/plugins/select2/js/select2.full.min.js`,
        `${assetPath}/plugins/jquery-validation/jquery.validate.min.js`,
        `${assetPath}/plugins/jquery-validation/additional-methods.min.js`,
        `${assetPath}/plugins/sweetalert2/sweetalert2.min.js`,
        `${assetPath}/plugins/toastr/toastr.min.js`,
        `${assetPath}/plugins/lazysizes/js/lazysizes.min.js`,
        `${assetPath}/plugins/nestable2/jquery.nestable.min.js`,
        `${assetPath}/plugins/cropper/cropper.min.js`,
        `${assetPath}/plugins/dropzone/dropzone.js`,
    ],
    `${publicPath}/js/vendor.min.js`
).mergeManifest();

mix.combine(
    [
        `${assetPath}/plugins/datatables/jquery.dataTables.min.js`,
        `${assetPath}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js`,
        `${assetPath}/plugins/datatables-responsive/js/dataTables.responsive.min.js`,
        `${assetPath}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js`,
        `${assetPath}/admin/js/datatable-helper.js`,
    ],
    `${publicPath}/js/datatables.min.js`
).mergeManifest();

mix.styles(
    [
        `${assetPath}/plugins/fontawesome/css/all.min.css`,
        `${assetPath}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css`,
        `${assetPath}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css`,
        `${assetPath}/plugins/jquery-ui/jquery-ui.min.css`,
        `${assetPath}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css`,
        `${assetPath}/plugins/select2/css/select2.min.css`,
        `${assetPath}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css`,
        `${assetPath}/plugins/sweetalert2/sweetalert2.min.css`,
        `${assetPath}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css`,
        `${assetPath}/plugins/toastr/toastr.min.css`,
        `${assetPath}/plugins/nestable2/jquery.nestable.min.css`,
        `${assetPath}/plugins/cropper/cropper.min.css`,
        `${assetPath}/plugins/dropzone/dropzone.css`,
    ],
    `${publicPath}/css/vendor.min.css`
).mergeManifest();

mix.combine(
    [
        `${assetPath}/frontend/js/online.js`,
        // `${assetPath}/frontend/js/firebase.js`,
    ],
    `${publicPath}/js/init.min.js`
)
    .mergeManifest();

mix.combine(
    [

        `${assetPath}/admin/dist/js/admin.min.js`,
        `${assetPath}/admin/js/helpers.js`,
        `${assetPath}/admin/js/common.js`,
        `${assetPath}/admin/js/datatable-helper.js`,
        `${assetPath}/admin/js/filemanager.js`,
        `${assetPath}/admin/js/form-ajax.js`,
        `${assetPath}/admin/js/load-select2.js`,
        `${assetPath}/admin/js/media.js`,
        `${assetPath}/admin/js/media-modal.js`,
        `${assetPath}/admin/js/menu.js`,
        `${assetPath}/admin/js/widget.js`,
    ],
    `${publicPath}/js/admin.min.js`
).mergeManifest();

mix.styles(
    [
        `${assetPath}/admin/dist/css/admin.css`,
        `${assetPath}/admin/css/media.css`,
        `${assetPath}/admin/css/menu.css`,
        `${assetPath}/admin/css/widget.css`,
        `${assetPath}/admin/css/page.css`,
        `${assetPath}/admin/css/pricing.css`,
        `${assetPath}/admin/css/custom.css`,
        `${assetPath}/admin/css/media-modal.css`,
        `${assetPath}/admin/css/modern-theme.css`,
    ],
    `${publicPath}/css/admin.min.css`
).mergeManifest();
