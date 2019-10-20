console.log('Hello Webpack Encore! Edit me in assets/js/shopList.js');

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

            $('.inputList').click(function(e){
            
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
        
            var button = document.getElementById('saveToPdfButton');

            button.addEventListener('click', function(e){
                console.log(list); 
                var start = document.getElementById("startPeriod").value;
                var end = document.getElementById("endPeriod").value;
                console.log(start); 
                console.log(end);

                var arrayStart = start.split('-');
                var startDate = new Date(arrayStart[2]+'-'+arrayStart[1]+'-'+arrayStart[0]);
        
                var arrayEnd = end.split('-');
                var endDate = new Date(arrayEnd[2]+'-'+arrayEnd[1]+'-'+arrayEnd[0]+'T00:00:00Z');

                console.log(list); 
                console.log(startDate); 
                console.log(endDate); 
                console.log(url);

                var listText = list.toString();
                console.log(listText);

                var url = encodeURI("/meal_planning/savetopdf/2019-10-20 00:00:00/2019-10-27 00:00:00/"+listText);
                //var url = "/meal_planning/savetopdf/"+startDate+"/"+endDate;

                console.log(this);
                this.setAttribute('href', url);



                /* var http_request = false;

                if (window.XMLHttpRequest) { // Mozilla, Safari,...
                    http_request = new XMLHttpRequest();
                } else if (window.ActiveXObject) { // IE
                    try {
                        http_request = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            http_request = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {}
                    }
                }

                if (!http_request) {
                    alert('Falla :( No es posible crear una instancia XMLHTTP');
                    return false;
                }
                //http_request.onreadystatechange = alertContents;
                http_request.open('POST', url, true);
                http_request.send(listText); */
                
                /* function alertContents() {
                    if (http_request.readyState == 4) {
                        if (http_request.status == 200 || http_request.status == 201) {
                            console.log(http_request.responseText);
                        } else {
                            console.log('Hubo problemas con la petición.');
                        }
                    }
                } */

            });

            /* $('#saveToPdfButton').click(function(event){
                console.log(list); 
                //event.preventDefault();
                var start = $('#startPeriod').val();
                var end = $('#endPeriod').val();
                console.log(start); 
                console.log(end);

                var arrayStart = start.split('-');
                var startDate = new Date(arrayStart[2]+'-'+arrayStart[1]+'-'+arrayStart[0]);
        
                var arrayEnd = end.split('-');
                var endDate = new Date(arrayEnd[2]+'-'+arrayEnd[1]+'-'+arrayEnd[0]+'T00:00:00Z');

                var url = encodeURI("/meal_planning/savetopdf/2019-10-20 00:00:00/2019-10-27 00:00:00");
        
                //var url = "/meal_planning/savetopdf/"+startDate+"/"+endDate;
                console.log(list); 
                console.log(startDate); 
                console.log(endDate); 
                console.log(url);
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: {
                        list: list,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data){   
                        console.log(data);
                    },
                    error: function(textStatus){
                        console.log(textStatus);
                    }
                }); 
            });  */

        });

    });

})(jQuery);

/* var $j = jQuery.noConflict();

// DATE PICKER TO SELECT MENU RANGE

$j(function() {
    $j( "#startPeriod" ).datepicker({
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
    $j( "#endPeriod" ).datepicker({
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
});

// CHECK INGREDIENTS
$j(document).ready(function() {
    var list = [];
    var ingredients = document.querySelectorAll('.inputList');

    $j('.inputList').click(function(e){
    
        $j(this).addClass("checked");
        var span = this.nextSibling;
        $j(span).css('text-decoration', 'line-through');
        $j(span).css('color', 'grey');

        if(list.length == 0){
            for (i = 0; i < ingredients.length; i++) {
                if(!ingredients[i].classList.contains('checked') && !list.includes(ingredients[i]) ){
                    list.push(ingredients[i]);
                }
            }
        } 
        console.log(this);
        if(list.includes(this)){
            var index = list.indexOf(this);
            console.log(index);
            list.splice(index, 1);
        }
        console.log(list);
    });
  
    $j('#saveToPdfButton').click(function(event){
        console.log(list); 
        event.preventDefault();
        var start = $j('#startPeriod').val();
        var end = $j('#endPeriod').val();
  
        var arrayStart = start.split('-');
        startDate = new Date(arrayStart[2]+'-'+arrayStart[1]+'-'+arrayStart[0]);
  
        var arrayEnd = end.split('-');
        endDate = new Date(arrayEnd[2]+'-'+arrayEnd[1]+'-'+arrayEnd[0]);
  
        var url = "/meal_planning/saveToPdf/"+startDate+"/"+endDate;
        console.log(list); 
        console.log(startDate); 
        console.log(endDate); 
        console.log(url);
        $j.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: {
                list: list,
                startDate: startDate,
                endDate: endDate
            },
            success: function(data){   
                console.log(data);
            }
        });   
    });
}); */