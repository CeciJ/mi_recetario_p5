console.log('Hello Webpack Encore! Edit me in assets/js/manageCorrespondances.js');

var jQuery = require('jquery');
var $ = require('jquery');
require('bootstrap');
var autocomplete = require('autocomplete.js');

(function($) {
    'use strict';

    $(function() {
        // AUTOCOMPLETE
        $(document).ready(function() {
            var client = algoliasearch('D4T2HAD5AA', 'fc16edcf60c2a963d29fde015c227872');
            var inputsNames = document.querySelectorAll('#corresponding_weights_unities_Ingredient'); 

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
        });    
    });
})(jQuery);