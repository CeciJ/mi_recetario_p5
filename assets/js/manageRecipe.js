console.log('Hello Webpack Encore! Edit me in assets/js/manageRecipe.js');

var jQuery = require('jquery');
var $ = require('jquery');
var autocomplete = require('autocomplete.js');

require('select2');
require('bootstrap');

$('select').select2();

(function($) {
  'use strict';

  $(function() {

    // DELETE IMAGES IN RECIPE FORM
    document.querySelectorAll('[data-delete]').forEach(a => {
        a.addEventListener('click', e => {
          e.preventDefault()
          fetch(a.getAttribute('href'), {
            method: 'DELETE',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({'_token': a.dataset.token})
          }).then(response => response.json())
            .then(data => {
              if (data.success) {
                a.parentNode.parentNode.removeChild(a.parentNode)
              } else {
                alert(data.error)
              }
            })
            .catch(e => alert(e))
        })
    });

    // ADD DYNAMICALLY RECIPE INGREDIENTS IN RECIPE FORM
    var $collectionHolder;
    // setup an "add a tag" link
    var $addIngredientButton = $('<button type="button" class="add_ingredient_link btn btn-primary">Ajouter un ingrédient</button>');
    var $newLinkDiv = $('<div class="addNewIngredient"></div>').append($addIngredientButton);

    $(document).ready(function() {
        $collectionHolder = $('div.manageIngredients');
        $collectionHolder.append($newLinkDiv);
        $collectionHolder.find('.rowIngredient').each(function() {
          addTagFormDeleteLink($(this));
        });

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        var compteur = 0;
        $addIngredientButton.on('click', function(e) {
            addTagForm($collectionHolder, $newLinkDiv);
          
            var divToAddClass = $collectionHolder[0].children[1].childNodes[compteur];
            $(divToAddClass).css('display', 'flex');
            compteur = compteur + 1;

            // AUTOCOMPLETE
            var client = algoliasearch('D4T2HAD5AA', 'fc16edcf60c2a963d29fde015c227872');

            var inputsNames = document.querySelectorAll('[id^="recipe_recipeIngredients_"][id$="_nameIngredient_name"]'); 
            jQuery.each(inputsNames, function(k, val){
              var id = val.id;
              var index = client.initIndex('prod_ingredients');
              autocomplete('input#'+id, { hint: false }, [
                {
                  source: autocomplete.sources.hits(index, { hitsPerPage: 5 }),
                  displayKey: 'name',
                  templates: {
                      suggestion: function(suggestion) {
                      return suggestion._highlightResult.name.value;
                      }
                  }
                }
              ]).on('autocomplete:selected', function(event, suggestion, dataset, context) {
                  //console.log(event, suggestion, dataset, context);
              });
            });

            var inputsUnits = document.querySelectorAll('[id^="recipe_recipeIngredients_"][id$="_unit_unit"]'); 
            jQuery.each(inputsUnits, function(k, val){
              var id = val.id;
              var index = client.initIndex('prod_units');
              autocomplete('input#'+id, { hint: false }, [
                {
                  source: autocomplete.sources.hits(index, { hitsPerPage: 5 }),
                  displayKey: 'unit',
                  templates: {
                      suggestion: function(suggestion) {
                      return suggestion._highlightResult.unit.value;
                      }
                  }
                }
              ]).on('autocomplete:selected', function(event, suggestion, dataset, context) {
                  //console.log(event, suggestion, dataset, context);
              });
            });  
            
        });

        $('.modal-trigger.add.dishType').click(function () {
          $('.modalAddDishType').modal();
          $('.modalAddDishType').modal('show');
        });
        $('.modal-trigger.add.foodType').click(function () {
            $('.modalAddFoodType').modal();
            $('.modalAddFoodType').modal('show');
        });
        $('.modal-trigger.add.option').click(function () {
            $('.modalAddOption').modal();
            $('.modalAddOption').modal('show');
        });
        $('.modal-trigger.add.measureUnit').click(function () {
            $('.modalAddMeasureUnit').modal();
            $('.modalAddMeasureUnit').modal('show');
        });
    });

    function addTagForm($collectionHolder, $newLinkDiv) {
      var prototype = $collectionHolder.data('prototype');
      var index = $collectionHolder.data('index');
      var newForm = prototype;
      newForm = newForm.replace(/__name__/g, index);
      $collectionHolder.data('index', index + 1);
      var $newFormDiv = $('<div class="newAddedIngredient"></div>').append(newForm);
      $newLinkDiv.before($newFormDiv);
      addTagFormDeleteLink($newFormDiv);
    }

    function addTagFormDeleteLink($newFormDiv) {
      var $removeFormButton = $('<button type="button" class="btn btn-perso deleteIngredient">Effacer</button>');
      $newFormDiv.append($removeFormButton);
  
      $removeFormButton.on('click', function(e) {
          $newFormDiv.remove();
      });
    }
      
  });

})(jQuery);