console.log('Hello Webpack Encore! Edit me in assets/js/manageRecipe.js');

var jQuery = require('jquery');
var $ = require('jquery');
//var algoliasearch = require('algoliasearch');
var autocomplete = require('autocomplete.js');

require('select2');
require('bootstrap');
//var autocomplete = require('jquery-ui/ui/widgets/autocomplete.js');
$('select').select2();

console.log(autocomplete);
/* console.log(algoliasearch);
 */
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

    // ADD RECIPE INGREDIENTS IN RECIPE FORM
    var $collectionHolder;
    // setup an "add a tag" link
    var $addIngredientButton = $('<button type="button" class="add_ingredient_link btn btn-primary">Ajouter un ingrédient</button>');
    var $newLinkDiv = $('<div></div>').append($addIngredientButton);

    $(document).ready(function() {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('div.manageIngredients');

        console.log($collectionHolder);

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkDiv);

        // add a delete link to all of the existing tag form elements
        $collectionHolder.find('.rowIngredient').each(function() {
          addTagFormDeleteLink($(this));
        });

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        var compteur = 0;
        $addIngredientButton.on('click', function(e) {
          //console.log(compteur);
            // add a new tag form (see next code block)
            addTagForm($collectionHolder, $newLinkDiv);
            console.log($collectionHolder[0].children[1]);
            
            var divToAddClass = $collectionHolder[0].children[1].childNodes[compteur];
            //console.log(divToAddClass);
            $(divToAddClass).css('display', 'flex');
            compteur = compteur + 1;

            // AUTOCOMPLETE

            var client = algoliasearch('D4T2HAD5AA', 'fc16edcf60c2a963d29fde015c227872');
            //var index = client.initIndex('ingredients');
            var index = client.initIndex('dev_ingredients');
            console.log(index);
            autocomplete('input.form-control', { hint: false }, [
            //autocomplete('.js-user-autocomplete', { hint: false }, [
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
                console.log(event, suggestion, dataset, context);
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

      // add a delete link to the new form
      addTagFormDeleteLink($newFormDiv);

    }

    function addTagFormDeleteLink($newFormDiv) {
      var $removeFormButton = $('<button type="button" class="btn btn-perso deleteIngredient">Effacer ingrédient</button>');
      $newFormDiv.append($removeFormButton);
  
      $removeFormButton.on('click', function(e) {
          // remove the li for the tag form
          $newFormDiv.remove();
      });
    }

    function submitEditOption(id){
      var oldName = $('#editOptionName-'+id).val();
      var path = "/option/" + id + "/edit";

      var inputName = $('#formOption-'+oldName);

      var newName = inputName.val();

      $.ajax({
          url: path,
          method: "POST",
          data: {
              id: id,
              newName: newName
          },
          success: function(data){
              alert('Modification effectuée avec succès');
              $('#modalEditOption-'+id).modal('hide');
              window.location.reload();
          }
      });
    }

    function submitEditDishType(id){
      var oldName = $('#editDishTypeName-'+id).val();
      var path = "/dishtype/" + id + "/edit";

      var inputName = $('#formDishType-'+oldName);

      var newName = inputName.val();

      $.ajax({
          url: path,
          method: "POST",
          data: {
              id: id,
              newName: newName
          },
          success: function(data){
              alert('Modification effectuée avec succès');
              $('#modalEditDishType-'+id).modal('hide');
              window.location.reload();
          }
      });
    }

    function submitEditFoodType(id){
      var oldName2 = $('#editFoodTypeName-'+id).val();
      var path = "/foodtype/" + id + "/edit";

      var inputName = $('#formFoodType-'+oldName2);

      var newName = inputName.val();

      $.ajax({
          url: path,
          method: "POST",
          data: {
              id: id,
              newName: newName
          },
          success: function(data){
              alert('Modification effectuée avec succès');
              $('#modalEditFoodType-'+id).modal('hide');
              window.location.reload();
          }
      });
    }
      
  });

})(jQuery);


/* $(document).ready(function() {
    var client = algoliasearch('D4T2HAD5AA', 'fc16edcf60c2a963d29fde015c227872');
    //var index = client.initIndex('ingredients');
    var index = client.initIndex('dev_ingredients');
    autocomplete('.js-user-autocomplete', { hint: false }, [
    //autocomplete('.js-user-autocomplete', { hint: false }, [
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
        console.log(event, suggestion, dataset, context);
    });
}); */