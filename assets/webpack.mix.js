const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.disableNotifications();
mix.version();

mix.options({
    postCss: [
        require('postcss-discard-comments') ({removeAll: true})
    ],
    terser: {extractComments: false}
});

mix.setPublicPath(`public/modules/withdraw`);

mix.styles([
    //
], 'public/modules/withdraw/css/main.min.css');

mix.combine([
    //
], 'public/modules/withdraw/js/main.min.js');
