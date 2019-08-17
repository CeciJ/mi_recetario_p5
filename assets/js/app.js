/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)

//import css from 'file.css';
var $ = require('jquery');
require('../css/app.css');
require('select2');

$('select').select2();
var $contactButton = $('#contactButton')
$contactButton.click(e => {
  e.preventDefault();
  $('#contactForm').slideDown();
  $contactButton.slideUp();
})

//saveToPdf
var day = $('#startPeriod_date_day option:selected').val();
var month = $('#startPeriod_date_month option:selected').val();
var year = $('#startPeriod_date_year option:selected').val();
console.log(day + '-' + month + '-' + year);

var endDay = $('#endPeriod_date_day option:selected').val();
var endMonth = $('#endPeriod_date_month option:selected').val();
var endYear = $('#endPeriod_date_year option:selected').val();
console.log(endDay + '-' + endMonth + '-' + endYear);

var startDate = new Date(year+'/'+month+'/'+day);
console.log(startDate);
var endDate = new Date(endYear+'/'+endMonth+'/'+endDay);
console.log(endDate);

/*
var pdfButton = $('#saveToPdfButton');
pdfButton.click(function(e){
  alert(startDate+'-'+endDate);
  $.ajax({
    url: "/meal_planning/savetopdf",
    type: "POST",
    dataType: 'json',
    data: {
        startPeriod: startDate, 
        endPeriod: endDate 
     },
    success: function (event) {   
        //eventSources[0].refetch();
        alert('Succès de l\'impression !');
        //window.location.reload(true);
    }
  });
});
*/

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
