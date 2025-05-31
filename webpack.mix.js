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
        `assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js`,
        `assets/plugins/select2/js/select2.full.min.js`,
        `assets/plugins/jquery-validation/jquery.validate.min.js`,
        `assets/plugins/jquery-validation/additional-methods.min.js`,
        `assets/dist/js/admin.min.js`,
    ],
    `assets/public/js/admin.min.js`
).mergeManifest();

mix.styles(
    [
        `assets/plugins/fontawesome/css/all.min.css`,
        `assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css`,
        `assets/plugins/select2/css/select2.min.css`,
        `assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css`,
        `assets/dist/css/admin.min.css`,
        `assets/dist/css/custom.css`,
    ],
    `assets/public/css/admin.min.css`
).mergeManifest();
