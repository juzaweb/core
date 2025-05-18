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
        `assets/dist/js/admin.min.js`,
    ],
    `assets/public/js/admin.min.js`
).mergeManifest();

mix.styles(
    [
        `assets/plugins/fontawesome/css/all.min.css`,
        `assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css`,
        `assets/dist/css/admin.min.css`,
    ],
    `assets/public/css/admin.min.css`
).mergeManifest();
