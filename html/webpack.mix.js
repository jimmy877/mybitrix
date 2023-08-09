let mix = require('laravel-mix');
//mix.js('src/app.js', 'dist');

/*let fs = require('fs');

let getFiles = function (dir) {
    // get all 'files' in this directory
    // filter directories
    return fs.readdirSync(dir).filter(file => {
        return fs.statSync(`${dir}/${file}`).isFile();
    });
};

getFiles('assets/scss/bootstrap/scss/').forEach(function (filepath) {
    mix.sass('assets/scss/bootstrap/scss/' + filepath, '/assets/scss/bootstrap/css/');
});*/

mix.sass('assets/scss/bootstrap/scss/bootstrap.scss', 'public/bootstrap/');
mix.sass('assets/scss/main.scss', 'public/style/');

mix.js('assets/vuetemplate/pages/index/main.js', 'public/js/output/index').vue({ version: 3 });
mix.js('assets/vuetemplate/pages/secondpage/main.js', 'public/js/output/secondpage').vue({ version: 3 });

mix.setPublicPath('assets');