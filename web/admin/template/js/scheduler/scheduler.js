import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new _fullcalendar_core__WEBPACK_IMPORTED_MODULE_0__["Calendar"](calendarEl, {
        plugins: [ _fullcalendar_daygrid__WEBPACK_IMPORTED_MODULE_2___default.a, _fullcalendar_timegrid__WEBPACK_IMPORTED_MODULE_3___default.a, _fullcalendar_list__WEBPACK_IMPORTED_MODULE_4___default.a ],
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

        eventClick: function(info) {

            //ziskat event podle ID
            info.event.id;
            console.log(info.event.title);

            document.getElementById("eventTitle").innerHTML = info.event.title;
            document.getElementById("event-from").innerHTML = info.event.start;
            document.getElementById("event-to").innerHTML = info.event.end;

            document.getElementById("description").innerHTML = info.event.description;

            $("#eventInfo").modal("show");
            //modal.style.display = "block";


        }
    });


    sendAjax("all", 5, calendar );
    calendar.render();


    $("#test").click(function () {
        for(var event in calendar.getEvents()){
            calendar.getEvents()[0].remove();
        }
        sendAjax("all", 5, calendar );
    });

});

/**
 * vraci dnesni datum
 * @returns {string}
 */
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
    let timeoutPromise = 1000000;

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
            if (response.msg === "ok") {
                //console.log(response.results);

                for(var i in response.results){
                    var newEvent = {
                        id: response.results[i].id,
                        title: response.results[i].nazev,
                        start: response.results[i].datum_od + 'T' + response.results[i].cas_od,
                        end: response.results[i].datum_do + 'T' + response.results[i].cas_do,
                        description: response.results[i].popis
                    };

                    calendar.addEvent(newEvent);
                    //calendar.events.push(newEvent);
                }

                //calendar.render();
                // melo by stacit jen tady meneni kalendare ale pro jistotu pridam volani funkce
                doResponseAction(select);
            }
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log( errorThrown );
        }
    });
}

function doResponseAction(select) {
    if (select == "all") {
        //do something
    }

}
