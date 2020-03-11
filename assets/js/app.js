/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

// IMPORT CSS FROM CSS FILE;
require('../css/app.css');

// require jQuery normally
const $ = require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');