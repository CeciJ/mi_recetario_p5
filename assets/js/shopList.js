console.log('Hello Webpack Encore! Edit me in assets/js/shopList.js');
var $ = require('jquery');

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
        $(document).ready(function() {
            var list = [];
            var ingredients = document.querySelectorAll('.inputList');

            // List with all ingredients 
            
            console.log(ingredients);
            ingredients.forEach(myFunction);

            function myFunction(item) {
                //alert(item.id);
                list.push(item.id);
            }

            console.log(list);

            // List if click to discard some ingredients

            $('.inputList').click(function(e){
            
                list.length = 0;
                console.log(list);
                $(this).addClass("checked");
                var span = this.nextSibling;
                var content = span.innerText;
                $(span).css('text-decoration', 'line-through');
                $(span).css('color', 'grey');

                var i;
                if(list.length == 0){
                    for (i = 0; i < ingredients.length; i++) {
                        if(!ingredients[i].classList.contains('checked') /* && !list.includes(ingredients[i]) */){
                            console.log(ingredients[i]);
                            console.log(ingredients[i].nextSibling.innerText);
                            list.push(ingredients[i].nextSibling.innerText);
                        }
                    }
                } 
                console.log(this.nextSibling.innerText);
                if(list.includes(this.nextSibling.innerText)){
                    var index = list.indexOf(this.nextSibling.innerText);
                    console.log(index);
                    list.splice(index, 1);
                }
                console.log(list);
            });
        
            // SAVE TO PDF
            var buttonExists = document.getElementById("saveToPdfButton");
            if(buttonExists){
                buttonExists.addEventListener('click', function(e){
                    var start = document.getElementById("startPeriod").value;
                    var end = document.getElementById("endPeriod").value;
                    console.log(start); 
                    console.log(end);
    
                    /* var arrayStart = start.split('-');
                    var startDate = new Date(arrayStart[2]+'-'+arrayStart[1]+'-'+arrayStart[0]+'T00:00:00Z');
                    var arrayEnd = end.split('-');
                    var endDate = new Date(arrayEnd[2]+'-'+arrayEnd[1]+'-'+arrayEnd[0]+'T00:00:00Z'); */
    
                    var startDate = start+'T00:00:00Z';
                    var endDate = end+'T00:00:00Z';
    
                    console.log(list); 
                    console.log(startDate); 
                    console.log(endDate); 
    
                    var listText = list.toString();
                    console.log(listText);
    
                    var url = encodeURI("/meal_planning/savetopdf/"+startDate+"/"+endDate+"/"+listText);
                    //var url = encodeURI("/meal_planning/savetopdf/2019-10-20 00:00:00/2019-10-27 00:00:00/"+listText);
                    //var url = "/meal_planning/savetopdf/"+startDate+"/"+endDate;
    
                    console.log(this);
                    console.log(url);
                    this.setAttribute('href', url);
    
                });

                // SEND BY MAIL

                var buttonMail = document.getElementById('sendMailButton');
                buttonMail.addEventListener('click', function(e){
                    console.log(list);
                    var start = document.getElementById("startPeriod").value;
                    var end = document.getElementById("endPeriod").value;
                    console.log(start); 
                    console.log(end);

                    /* var arrayStart = start.split('-');
                    var startDate = new Date(arrayStart[2]+'-'+arrayStart[1]+'-'+arrayStart[0]);
                    var arrayEnd = end.split('-');
                    var endDate = new Date(arrayEnd[2]+'-'+arrayEnd[1]+'-'+arrayEnd[0]+'T00:00:00Z'); */
                    
                    var startDate = start+'T00:00:00Z';
                    var endDate = end+'T00:00:00Z';

                    console.log(list); 
                    console.log(startDate); 
                    console.log(endDate); 

                    var listText = list.toString();
                    console.log(listText);

                    var url = encodeURI("/meal_planning/sendbymail/"+startDate+"/"+endDate+"/"+listText);
                    console.log(url);
                    console.log(this);
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