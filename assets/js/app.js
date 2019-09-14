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

var $collectionHolder;
    // setup an "add a tag" link
    var $addIngredientButton = $('<button type="button" class="add_ingredient_link btn btn-primary">Ajouter un ingrédient</button>');
    var $newLinkDiv = $('<div></div>').append($addIngredientButton);

    jQuery(document).ready(function() {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('div.manageIngredients');

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkDiv);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        var compteur = 0;
        $addIngredientButton.on('click', function(e) {
          //console.log(compteur);
            // add a new tag form (see next code block)
            addTagForm($collectionHolder, $newLinkDiv);

            /*
            console.log($collectionHolder[0].children[1]);
            */
            var divToAddClass = $collectionHolder[0].children[1].childNodes[compteur];
            //console.log(divToAddClass);
            $(divToAddClass).css('display', 'flex');
            compteur = compteur + 1;
        });
    });

    function addTagForm($collectionHolder, $newLinkDiv) {
      // Get the data-prototype explained earlier
      var prototype = $collectionHolder.data('prototype');

      // get the new index
      var index = $collectionHolder.data('index');

      var newForm = prototype;
      // You need this only if you didn't set 'label' => false in your tags field in TaskType
      // Replace '__name__label__' in the prototype's HTML to
      // instead be a number based on how many items we have
      // newForm = newForm.replace(/__name__label__/g, index);

      // Replace '__name__' in the prototype's HTML to
      // instead be a number based on how many items we have
      newForm = newForm.replace(/__name__/g, index);

      // increase the index with one for the next item
      $collectionHolder.data('index', index + 1);

      // Display the form in the page in an li, before the "Add a tag" link li
      var $newFormDiv = $('<div></div>').append(newForm);

      $newLinkDiv.before($newFormDiv);

    }