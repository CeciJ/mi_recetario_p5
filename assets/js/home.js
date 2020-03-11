console.log('Hello Webpack Encore! Edit me in assets/js/home.js');
var $ = require('jquery');
require('bootstrap');

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin, { Draggable } from '@fullcalendar/interaction';

// FULLCALENDAR
$(document).ready(function() {
    var calendarEl = document.getElementById('calendar');
    var containerEl = document.getElementById('external-events');
    var today = new Date();
    var titleName;
    var titleEvent;
  
    $('#recipient-name').change(function(e){
        titleEvent = $('select').val();
        titleName = $('select option:selected').text();
    });
  
    // initialize the external events  
    new Draggable(containerEl, {
        itemSelector: '.fc-event',
        eventData: function(eventEl) {
            return {
                title: eventEl.innerText
            };
        }
    });
  
    // initialize the calendar  
    var calendar = new Calendar(calendarEl, {
        plugins: [ interactionPlugin, dayGridPlugin ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth, dayGridWeek'
        },
        validRange: {
            start: today
        },
        timeZone: 'local',
        editable: true,
        defaultView: 'dayGridMonth',
        selectable: true,
        locale: 'fr',
        firstDay: 1,
        fixedWeekCount: false,
        buttonText: {
            today:    'Aujourd\'hui',
            month:    'mois',
            week:     'semaine',
            day:      'jour'            
        },
        droppable: true, // this allows things to be dropped onto the calendar
        eventSources: [
            {
                url: "/fc-load-events",
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({})
                },
                failure: () => {
                    alert("There was an error while fetching FullCalendar!");
                },
            }
        ],
        dateClick: function(event) {
            jQuery.noConflict();
            (function ($) {
                $('#exampleModal').modal('show');
                var date = event.dateStr;
                $('#recipient-day').html(date);
            }
            )(jQuery);
        },
        drop: function(event) {
            var idCategorie = event.draggedEl.attributes[1].nodeValue;
            var checkbox = document.getElementById('drop-remove'+idCategorie);
            if (checkbox.checked) {
                event.draggedEl.parentNode.removeChild(event.draggedEl);
            }
  
            var eventTitle = event.draggedEl.innerText;
            var eventDate = event.date;
            var eventDateStart = new Date(eventDate);
            var eventSources = calendar.getEventSources();
            var element = event.draggedEl;
  
            $.ajax({
                url: "/meal_planning/new",
                type: "POST",
                dataType: 'json',
                data: {
                    beginAt: eventDateStart, 
                    title: eventTitle 
                    },
                success: function (event) {
                    var eventId = event.id;
                    var events = calendar.getEvents(eventId);
                    jQuery.each(events, function(k, event) {
                        var url = event._def.url;
                        if(url.length == 0){
                            event.remove();
                            eventSources[0].refetch();
                        }
                    }); 
                    alert('Recette ajoutée avec succès !');
                }
            });
        },
        eventClick: function(event) {
            event.jsEvent.preventDefault();
            if(confirm("Êtes-vous sûr de vouloir supprimer ce repas du calendrier ?")){
                doSubmitDelete(event);
            };
        },
        eventDrop: function(eventDropInfo){
            
            var eventTitle = eventDropInfo.event.title;
            var eventUrl = eventDropInfo.event.url;
            var splitUrl = eventUrl.split('/');
            var idMealPlanning = splitUrl[2];
            var eventDiff = eventDropInfo.delta.days;
  
            var newDate = eventDropInfo.event._instance.range.start;
  
            $.ajax({
                url: "/meal_planning/edit/"+idMealPlanning,
                type: "POST",
                dataType: 'json',
                data: {
                    date: newDate, 
                    title: eventTitle,
                    id: idMealPlanning 
                },
                success: function (event) {   
                    alert('Menu décalé avec succès !');
                }
            });
  
        },
        eventDragStop: function(event) {
            var url = event.event.url;
            var urlSplit = url.split("/");
            var id = urlSplit[2];
  
            var eventSources = calendar.getEventSources();
  
            var trashEl = $('#calendarTrash');
            var ofs = trashEl.offset();
  
            var jsEvent = event.jsEvent;
  
            var x1 = ofs.left;
            var x2 = ofs.left + trashEl.outerWidth(true);
            var y1 = ofs.top;
            var y2 = ofs.top + trashEl.outerHeight(true);
  
            if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 &&
                jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
  
                $.ajax({
                    url: "/meal_planning/deletedragnout/" + id,
                    type: "DELETE",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function (event) {                            
                        eventSources[0].refetch();
                        alert('Menu effacé avec succès !');
                    }
                });
            }
        },
        eventReceive: function(event) {
            //event.view.el.style.color = 'white';
        }
    });
  
    calendar.render();
  
    $('#addButtonModal').click(function(e){
    // We don't want this to act as a link so cancel the link action
        e.preventDefault();
  
        doSubmit();
    });
  
    function doSubmit(){
        var dateEvent = $('#recipient-day').text();
        jQuery.noConflict();
        (function ($) {
            $("#exampleModal").modal('hide');
        }
        )(jQuery);

        var eventSources = calendar.getEventSources();
        
        $.ajax({
            url: "/meal_planning/new",
            type: "POST",
            dataType: 'json',
            data: {
                beginAt: dateEvent, 
                title: titleName,
            },
            success: function (data, response, event, date) {
                alert('Recette ajoutée avec succès !');
                eventSources[0].refetch();
            },
            error: function (data) {
                alert('Erreur dans l\'ajout de la recette');
            }
        });
    }

    function doSubmitDelete(event){
        var mealPlanningName = event.event.title;
        var url = event.event.url;
        var urlSplit = url.split("/");
        var id = urlSplit[2];
        var eventSources = calendar.getEventSources();
        $.ajax({
            url: "/meal_planning/delete/" + id,
            type: "DELETE",
            dataType: 'json',
            data: {
                id: id
            },
            success: function (event) {                            
                eventSources[0].refetch();
                alert('Menu effacé avec succès !');
            }
        });
    }

});