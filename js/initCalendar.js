$(document).ready(function () {
    init_calendar();
})

function init_calendar() {
    var initialLocaleCode = 'es';
    var calendarEl = document.getElementById('calendar');
    var e = new Date,
        i = e.getDate(),
        n = e.getMonth(),
        r = e.getFullYear();
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth' // listMonth,timeGridDay,timeGridWeek
        },
        defaultView: 'dayGridMonth', // dayGridMonth, dayGridWeek, timeGridDay, listWeek
        // defaultDate: '2019-08-12',
        locale: initialLocaleCode,
        buttonIcons: true, // show the prev/next text
        weekNumbers: false,
        navLinks: false, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: [
            // Events Ajax
        ],
        // views: {
        //     dayGridMonth: { // name of view
        //      titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
        //      // other view-specific options here
        //     }
        // }
    });
    calendar.render();
}