/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

// IMPORT CSS FROM CSS FILE;
var $ = require('jquery');

require('../css/app.css');
require('select2');

//var $j = jQuery.noConflict();

// IMPORT PLUGIN JS SELECT
$('select').select2();

// FORMULAIRE DE CONTACT
var $contactButton = $('#contactButton')
$contactButton.click(e => {
  e.preventDefault();
  $('#contactForm').slideDown();
  $contactButton.slideUp();
})