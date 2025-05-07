const mix = require("laravel-mix");

mix.combine(
    [
        `${__dirname}/plugins/jquery/jquery.min.js`,
        `${__dirname}/plugins/bootstrap/js/bootstrap.bundle.min.js`,
        `${__dirname}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js`,
        `${__dirname}/dist/js/adminlte.min.js`,
    ],
    `public/assets/core/js/admin.min.js`
);

mix.styles(
    [
        `${__dirname}/plugins/fontawesome/css/all.min.css`,
        `${__dirname}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css`,
        `${__dirname}/dist/css/adminlte.min.css`,
    ],
    `public/assets/core/css/admin.min.css`
);
