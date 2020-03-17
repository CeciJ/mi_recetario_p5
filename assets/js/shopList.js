console.log('Hello Webpack Encore! Edit me in assets/js/shopList.js');

require('jquery-ui');
require('jquery-ui/ui/widgets/datepicker.js');
require('bootstrap');

(function($) {
    'use strict';
  
    $(function() {
    
        // DATE PICKER TO SELECT MENU RANGE
        $( "#startPeriod" ).datepicker({
            dateFormat: "dd-mm-yy",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            firstDay: 1
        });
        $( "#endPeriod" ).datepicker({
            dateFormat: "dd-mm-yy",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            firstDay: 1
        });

        // CHECK INGREDIENTS
        function generateListForMailAndPdf(ingredients){
            var list = [];
            var i;
            if(list.length == 0){
                for (i = 0; i < ingredients.length; i++) {
                    if(!ingredients[i].classList.contains('checked')){
                        list.push(ingredients[i].nextSibling.innerText);
                    }
                }
            } 
            return list;
        }

        $(document).ready(function() {

            var ingredients = document.querySelectorAll('.inputList');

            $('.inputList').click(function(e){
                $(this).toggleClass("checked");
                var span = this.nextSibling;
                $(span).toggleClass("ingredientChecked");
            });

            // SAVE TO PDF
            
            var buttonExists = document.getElementById("saveToPdfButton");
            if(buttonExists){
                buttonExists.addEventListener('click', function(e){
                    var list = generateListForMailAndPdf(ingredients);
                    console.log(list);
                    var start = document.getElementById("startPeriod").value;
                    var end = document.getElementById("endPeriod").value;
                
                    var startDate = start+'T00:00:00Z';
                    var endDate = end+'T00:00:00Z';    
                    var listText = list.toString();
    
                    var url = encodeURI("/meal_planning/savetopdf/"+startDate+"/"+endDate+"/"+listText);
                    
                    this.setAttribute('href', url);
    
                });

                // SEND BY MAIL

                var buttonMail = document.getElementById('sendMailButton');
                buttonMail.addEventListener('click', function(e){
                    var list = generateListForMailAndPdf(ingredients);
                    
                    var start = document.getElementById("startPeriod").value;
                    var end = document.getElementById("endPeriod").value;
                    
                    var startDate = start+'T00:00:00Z';
                    var endDate = end+'T00:00:00Z';
                    var listText = list.toString();

                    var url = encodeURI("/meal_planning/sendbymail/"+startDate+"/"+endDate+"/"+listText);
                    this.setAttribute('href', url);
                });

                // HIDE SUCCESS MSG

                var message = document.getElementById('msgSuccessListSentByMail');
                if (message) {
                    setTimeout(function(){ 
                        message.remove(); }, 
                        5000
                    );
                }
            }

        });

    });

})(jQuery);