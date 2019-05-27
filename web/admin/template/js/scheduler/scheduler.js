import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar (calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        defaultDate: getDate(),
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: [],
        locale: 'cs',

        eventClick: function(info) {


            document.getElementById("eventTitle").innerHTML = info.event.title;

            sendAjax("service", info.event.id, calendar);

            $("#eventInfo").modal("show");
            //modal.style.display = "block";


        }
    });


    calendar.setOption('locale', 'cs');

    sendAjax("all", 5, calendar );
    calendar.render();

    sendAjax("types", 5, calendar);
    sendAjax("obInService", 5, calendar);


    $("#test").click(function () {
        for(var event in calendar.getEvents()){
            calendar.getEvents()[0].remove();
        }
        sendAjax("all", 5, calendar );
    });

    $("#filter").click(function () {
        for(var event in calendar.getEvents()){
            calendar.getEvents()[0].remove();
        }

        if(document.getElementById("chooseService").value.length != 0){
            var type = document.getElementById("chooseService").value;
            var datalist = document.getElementById("datalist-service");
            var options = datalist.getElementsByTagName("option");

            var i;
            for(i = 0; i < options.length; i++){
                if(options[i].value == type){
                    console.log(options[i].id);

                    sendAjax("type", options[i].id, calendar);
                }
            }
        }

        if(document.getElementById("chooseObyvatel").value.length != 0){

            var type = document.getElementById("chooseObyvatel").value;
            var datalist = document.getElementById("datalist-obyvatel");
            var options = datalist.getElementsByTagName("option");

            var i;
            for(i = 0; i < options.length; i++){
                if(options[i].value == type){
                    console.log(options[i].id);

                    sendAjax("obyvatel", options[i].id, calendar);
                }
            }
        }

        if(document.getElementById("chooseObyvatel").value.length == 0 && document.getElementById("chooseService").value.length == 0){
            sendAjax("all", 5, calendar);
        }

    });

});

/**
 * vraci dnesni datum
 * @returns {string}
 */
function getDate() {
    var d = new Date();

    var month = d.getMonth()+1;
    var day = d.getDate();

    var output = d.getFullYear() + '-' +
        ((''+month).length<2 ? '0' : '') + month + '-' +
        ((''+day).length<2 ? '0' : '') + day ;
    return output;
}


/**
 * posles dotaz na api
 * @param select - definice toho co chces ziskat
 * @param userInputString - upresnujici informace {napr id}
 */
function sendAjax (select, userInputString, calendar) {
    let timeoutPromise = 10000;

    $.ajax({
        url: "/admin/index.php/plugin/scheduler-api" ,
        dataType: 'text',
        type: 'post',
        contentType: 'application/json',
        data: JSON.stringify({
            select: select,
            id: userInputString,
            timeout: timeoutPromise
        }),
        success: function( data, textStatus, jQxhr ){
            var response = JSON.parse(data);
            //console.log(data);
            // calendar.events = new Array();
            console.log(response.msg);
            if (response.msg === "ok") {

                // melo by stacit jen tady meneni kalendare ale pro jistotu pridam volani funkce
                doResponseAction(select, response.results, calendar);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log( errorThrown );
        }
    });
}

/**
 * vykonani akce
 * @param select dotaz
 * @param results vysledek
 * @param calendar objekt kalendare
 */
function doResponseAction(select, results, calendar) {
    if (select == "all" || select == "type" || select == "obyvatel") {
        for (var i in results) {
            var newEvent = {
                id: results[i].id,
                title: results[i].nazev,
                start: results[i].datum_od + 'T' + results[i].cas_od,
                end: results[i].datum_do + 'T' + results[i].cas_do,
                description: results[i].popis
            };

            calendar.addEvent(newEvent);
        }

        calendar.render();
    }

    if (select == "types") {
        var dropdown = document.getElementById("datalist-service");

        for (var i in results) {
            dropdown.innerHTML += "<option id=" + results[i].id + ">" + results[i].nazev + "</option>";
        }
    }

    if (select == "obInService") {
        var dropdown = document.getElementById("datalist-obyvatel");

        for (var i in results) {
            dropdown.innerHTML += "<option id=" + results[i].id + ">" + results[i].jmeno + " " + results[i].prijmeni + "</option>";
        }
    }

    if (select == "service"){
        document.getElementById("event-from").innerHTML = results[0].datum_od + " " + results[0].cas_od;
        document.getElementById("event-to").innerHTML = results[0].datum_do + " " + results[0].cas_do;
        document.getElementById("event-obyvatel").innerHTML = results[0].jmeno + " " + results[0].prijmeni;

        document.getElementById("description").innerHTML = results[0].popis;
    }
}