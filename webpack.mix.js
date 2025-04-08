const mix = require('laravel-mix');
require('@tinypixelco/laravel-mix-wp-blocks');

mix.block('blocks/index.js', 'blocks-build');

// Get all JS files in assets/js directory
const fs = require('fs');
const path = require('path');

// Function to get all JS files in a directory
const getJsFiles = (dir) => {
    const jsFiles = {};
    fs.readdirSync(dir)
        .filter(file => file.endsWith('.js') && !file.includes('.bundle'))
        .forEach(file => {
            const filename = path.basename(file, '.js');
            jsFiles[filename] = path.join(dir, file);
        });
    return jsFiles;
};

// Function to get all SCSS files in a directory
const getScssFiles = (dir) => {
    const scssFiles = {};
    fs.readdirSync(dir)
        .filter(file => file.endsWith('.scss'))
        .forEach(file => {
            const filename = path.basename(file, '.scss');
            scssFiles[filename] = path.join(dir, file);
        });
    return scssFiles;
};

// Get JS and SCSS files
const jsFiles = getJsFiles('assets/js');
const scssFiles = getScssFiles('assets/css');

// Process JS files
Object.keys(jsFiles).forEach(filename => {
    mix.js(jsFiles[filename], `assets/js/${filename}.bundle.js`);
});

// Process SCSS files
Object.keys(scssFiles).forEach(filename => {
    mix.sass(scssFiles[filename], `assets/css/${filename}.bundle.css`);
});

mix.react()
//    .setPublicPath('assets')

// for admin 
mix.js('admin/js/admin.js', 'assets/js/admin.bundle.js')
   .sass('admin/css/admin.scss', 'assets/css/admin.bundle.css');
