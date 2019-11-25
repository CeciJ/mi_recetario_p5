/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

const $ = require('jquery');
const jQuery = require('jquery');

// IMPORT CSS FROM CSS FILE;
require('../css/app.css');

// IMPORT PLUGIN JS SELECT
require('select2');
$('select').select2();

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

// FULLCALENDAR JQUERY
document.addEventListener('DOMContentLoaded', function() {
  var Calendar = FullCalendar.Calendar;
  var Draggable = FullCalendarInteraction.Draggable;

  var containerEl = document.getElementById('external-events');
  var calendarEl = document.getElementById('calendar');
  var checkbox = document.getElementById('drop-remove');

  var today = new Date();

  var titleName;

  $('#recipient-name').change(function(e){
      titleEvent = $('select').val();
      titleName = $('select option:selected').text();
      console.log(titleName);
  });

  // initialize the external events
  // -----------------------------------------------------------------

  new Draggable(containerEl, {
      itemSelector: '.fc-event',
      eventData: function(eventEl) {
          return {
              title: eventEl.innerText
          };
      }
  });

  // initialize the calendar
  // -----------------------------------------------------------------

  var calendar = new Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid' ],
      //plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      header: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth'
          //right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      validRange: {
          start: today
      },
      timeZone: 'local',
      //timeZone: 'UTC',
      editable: true,
      defaultView: 'dayGridMonth',
      selectable: true,
      locale: 'fr',
      firstDay: 1,
      buttonText: {
          today:    'Aujourd\'hui',
          month:    'mois',
          week:     'semaine',
          day:      'jour'            
      },
      droppable: true, // this allows things to be dropped onto the calendar
      eventSources: [
          {
              /* url: "{{ path('fc_load_events') }}", */
              url: "/fc-load-events",
              method: "POST",
              extraParams: {
                  filters: JSON.stringify({})
              },
              failure: () => {
                  // alert("There was an error while fetching FullCalendar!");
              },
          }
      ],
      dateClick: function(event) {
          console.log(event);
          $('#exampleModal').modal('show');
          $('#dayModal').html(event.dateStr);
      },
      /*
      dateClick: function(event) {
          console.log(event);
          console.log(event.jsEvent);
          $('#exampleModal').modal('show');

          var dateEvent = event.date;
          console.log(dateEvent);
          console.log(titleName);
      
          //var eventSources = calendar.getEventSources();
          //event.jsEvent.preventDefault();
          
          $('#addButtonModal').click(function(e){
              if(titleName == null) {
                  alert('Veuillez sélectionner votre recette');
              }

              $.ajax({
                  url: "/meal_planning/new",
                  type: "POST",
                  dataType: 'json',
                  data: {
                      beginAt: dateEvent, 
                      title: titleName,
                      origin: 'dateClick', 
                      },
                  success: function (data, response, event, date) {
                      alert('Recette ajoutée avec succès !');
                      $('#exampleModal').modal('hide');
                      calendar.refetchEvents();
                      /*calendar.fullCalendar('renderEvent',
                      {
                          title: titleName,
                          start: dateEvent,
                          //end: thedate1
                      }, true);
                  }
              /*
              }).done(function(event) {
                  //eventSources[0].refetch();
                  alert('Recette ajoutée avec succès !');
                  $('#exampleModal').modal('hide');
                  //eventSources[0].refetch();
                  titleName = '';
                  dateEvent = '';
                  console.log(event);
                  //event.jsEvent.stopPropagation();
                  //window.location.reload(true)
                  
              });
              
              //event.jsEvent.stopPropagation();
              //eventSources[0].refetch();
              //calendar.refetchEvents();
          });

          //calendar.refetchEvents();
          //var eventSources = calendar.getEventSources();
          //eventSources[0].refetch();
          //event.jsEvent.preventDefault();
          //event.jsEvent.stopPropagation();
      },
      */
      drop: function(event) {
      // is the "remove after drop" checkbox checked?
          if (checkbox.checked) {
              // if so, remove the element from the "Draggable Events" list
              event.draggedEl.parentNode.removeChild(event.draggedEl);
          }
          console.log(event);
          console.log(event.date);

          var eventTitle = event.draggedEl.innerText;
          var eventDate = event.date;
          var eventDateStart = new Date(eventDate);
          var eventSources = calendar.getEventSources();

          console.log(eventDateStart);
          console.log(eventSources);

          $.ajax({
              url: "/meal_planning/new",
              type: "POST",
              dataType: 'json',
              data: {
                  beginAt: eventDateStart, 
                  title: eventTitle 
                  },
              success: function (event) {   
                  //eventSources[0].refetch();
                  alert('Recette ajoutée avec succès !');
                  //window.location.reload(true);
              }
          });
          //eventSources[0].refetch();
      },
      eventClick: function(info) {
          info.jsEvent.preventDefault();
          var mealPlanningName = info.event.title;

          alert('Event: ' + info.event.title);
          alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
          alert('View: ' + info.view.type);
      },
      eventDrop: function(eventDropInfo){
          console.log(eventDropInfo);
          
          var eventTitle = eventDropInfo.event.title;
          var eventUrl = eventDropInfo.event.url;
          var splitUrl = eventUrl.split('/');
          var idMealPlanning = splitUrl[2];
          var eventDiff = eventDropInfo.delta.days;

          var newDate = eventDropInfo.event._instance.range.start;
          console.log(idMealPlanning);
          console.log(eventTitle);
          console.log(eventDiff);

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
                  //eventSources[0].refetch();
                  alert('Menu décalé avec succès !');
              }
          });

      },
      eventDragStop: function(event) {
          console.log(event.event.url);
          var url = event.event.url;
          var urlSplit = url.split("/");
          var id = urlSplit[2];
          console.log(id);

          var eventSources = calendar.getEventSources();

          var trashEl = $('#calendarTrash');
          var ofs = trashEl.offset();

          jsEvent = event.jsEvent;

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
                      //event.el.parentNode.removeChild(event.el);
                      //window.location.reload(true);
                  }
              });

              //event.el.parentNode.removeChild(event.el);
              //alert('Évènement supprimé !');
          }
      },
      eventReceive: function(event) {
          console.log(event);
          console.log(event.view.el);
          //event.view.el.style.color = 'white';
      }
  });

  calendar.render();
  //calendar.refetchEvents();

  $('#addButtonModal').click(function(e){
  // We don't want this to act as a link so cancel the link action
      e.preventDefault();

      doSubmit();
  });

  function doSubmit(){
      console.log(titleName);
      var dateEvent = $('#dayModal').text();
      console.log(dateEvent);
      $("#exampleModal").modal('hide');
      
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
              calendar.refetchEvents();
          },
          error: function (data) {
              alert('Erreur dans l\'ajout de la recette');
          }
      });
      /*.done(function(event) {
          //eventSources[0].refetch();
          alert('Recette ajoutée avec succès !');
          $('#exampleModal').modal('hide');
          //eventSources[0].refetch();
          titleName = '';
          dateEvent = '';
          console.log(event);
          //event.jsEvent.stopPropagation();
          //window.location.reload(true)
          
      });
      */
  }
});

/* // FORMULAIRE DE CONTACT
var $contactButton = $('#contactButton')
$contactButton.click(e => {
  e.preventDefault();
  $('#contactForm').slideDown();
  $contactButton.slideUp();
}) */