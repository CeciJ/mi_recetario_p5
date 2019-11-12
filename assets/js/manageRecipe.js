console.log('Hello Webpack Encore! Edit me in assets/js/manageRecipe.js');

var $ = require('jquery');
var autocomplete = require('autocomplete.js');

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
            console.log($collectionHolder[0].children[1]);
            
            var divToAddClass = $collectionHolder[0].children[1].childNodes[compteur];
            //console.log(divToAddClass);
            $(divToAddClass).css('display', 'flex');
            compteur = compteur + 1;

            // AUTOCOMPLETE

            var client = algoliasearch('D4T2HAD5AA', 'fc16edcf60c2a963d29fde015c227872');
            //var index = client.initIndex('ingredients');
            var index = client.initIndex('dev_ingredients');
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
      
  });

})(jQuery);