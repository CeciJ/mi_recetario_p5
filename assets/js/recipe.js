// Autocomplete
console.log('Hello Webpack Encore! Edit me in assets/js/recipe.js');

require('bootstrap');

(function($) {
    $(document).ready(function() {
        $('.js-user-autocomplete').autocomplete({hint: false}, [
            {
                source: function(query, cb) {
                    cb([
                        {value: 'foo'},
                        {value: 'bar'}
                    ])
                }
            }
        ]);
    });
})(jQuery);