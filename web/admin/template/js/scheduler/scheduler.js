import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        defaultDate: getDate(),
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: [
            {
                title: 'All Day Event',
                start: '2018-01-01',
            },
            {
                title: 'Long Event',
                start: '2018-01-07',
                end: '2018-01-10'
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: '2018-01-09T16:00:00'
            },
            {
                id: 999,
                title: 'Repeating Event',
                start: '2018-01-16T16:00:00'
            },
            {
                title: 'Conference',
                start: '2018-01-11',
                end: '2018-01-13'
            },
            {
                title: 'Meeting',
                start: '2018-01-12T10:30:00',
                end: '2018-01-12T12:30:00'
            },
            {
                title: 'Lunch',
                start: '2018-01-12T12:00:00'
            },
            {
                title: 'Meeting',
                start: '2018-01-12T14:30:00'
            },
            {
                title: 'Happy Hour',
                start: '2018-01-12T17:30:00'
            },
            {
                title: 'Dinner',
                start: '2018-01-12T20:00:00'
            },
            {
                title: 'Birthday Party',
                start: '2018-01-13T07:00:00'
            },
            {
                title: 'Click for Google',
                url: 'http://google.com/',
                start: '2018-01-28'
            }
        ]
    });

    calendar.render();

    $("#test").click(function () {
        sendAjax("all", 5 );
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
function sendAjax (select, userInputString) {
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
            if (response.msg === "ok") {
                console.log(response.results);
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