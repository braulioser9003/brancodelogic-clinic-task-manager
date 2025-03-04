/*
Template Name: Minton - Admin & Dashboard Template
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Calendar init js
*/



!function($) {
    "use strict";

    var CalendarApp = function() {
        this.$body = $("body")
        this.$modal = $('#event-modal'),
        this.$calendar = $('#calendar'),
        this.$formEvent = $("#form-event"),
        this.$btnNewEvent = $("#btn-new-event"),
        this.$btnDeleteEvent = $("#btn-delete-event"),
        this.$btnSaveEvent = $("#btn-save-event"),
        this.$modalTitle = $("#modal-title"),
        this.$calendarObj = null,
        this.$selectedEvent = null,
        this.$newEventData = null
    };




    /* on select */
    CalendarApp.prototype.onSelect = function (info) {
        this.$formEvent[0].reset();
        this.$formEvent.removeClass("was-validated");

        this.$selectedEvent = null;
        this.$newEventData = info;
        this.$btnDeleteEvent.hide();
        this.$modalTitle.text('Add New Event');

        this.$modal.modal({
            backdrop: 'static'
        });
        this.$calendarObj.unselect();
    },

    /* Initializing */
    CalendarApp.prototype.init = function() {

        /*  Initialize the calendar  */
        var today = new Date($.now());

        var Draggable = FullCalendarInteraction.Draggable;
        var externalEventContainerEl = document.getElementById('external-events');



        var defaultEvents =  [{
                title: 'Meeting with Mr. Shreyu',
                start: new Date($.now() + 158000000),
                end: new Date($.now() + 338000000),
                className: 'bg-warning'
            },
            {
                title: 'Interview - Backend Engineer',
                start: today,
                end: today,
                className: 'bg-success'
            },
            {
                title: 'Phone Screen - Frontend Engineer',
                start: new Date($.now() + 168000000),
                className: 'bg-info'
            },
            {
                title: 'Buy Design Assets',
                start: new Date($.now() + 338000000),
                end: new Date($.now() + 338000000 * 1.2),
                className: 'bg-primary',
            }];

        var $this = this;

        // cal - init
        $this.$calendarObj = new FullCalendar.Calendar($this.$calendar[0], {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
            slotDuration: '00:15:00', /* If we want to split day time each 15minutes */
            minTime: '08:00:00',
            maxTime: '19:00:00',
            themeSystem: 'bootstrap',
            bootstrapFontAwesome: false,
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                day: 'Day',
                list: 'List',
                prev: 'Prev',
                next: 'Next'
            },
            defaultView: 'dayGridMonth',
            handleWindowResize: true,
            height: $(window).height() - 200,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            events: defaultEvents,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            eventLimit: true, // allow "more" link when too many events
            selectable: true,
            dateClick: function (info) { $this.onSelect(info);
                var myModal = new bootstrap.Modal(document.getElementById('event-modal'), {});
                myModal.toggle();

                $('#event-datestart').val(info.dateStr + 'T08:00'); // Default start time
                $('#event-dateend').val(info.dateStr + 'T09:00'); // Default end time
             },
            eventClick: function(info) {
                $this.onEventClick(info);
                var myModal = new bootstrap.Modal(document.getElementById('event-modal'), {});
                myModal.toggle();
            },
            eventDrop: function (event, delta, revertFunc) {
                console.log(defaultEvents);
            },
        });

        $this.$calendarObj.render();

        // on new event button click
        $this.$btnNewEvent.on('click', function(e) {
            $this.onSelect({date: new Date(), allDay: true});
            var myModal = new bootstrap.Modal(document.getElementById('event-modal'), {});
            myModal.toggle();
        });

        // Handle form submission
        $('#form-event').on('submit', function(e) {
            e.preventDefault();
            var form = this;

            // Change button text to spinner
            var $saveButton = $('#btn-save-event');
            var originalButtonText = $saveButton.html();
            $saveButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...').prop('disabled', true);

            // validation

            var formData = {
                title: $("#event-title").val(),
                description: $("#event-description").val(),
                date_start: $("#event-datestart").val(),
                date_end: $("#event-dateend").val(),
                location: $("#event-location").val(),
                type_reminder: $("#event-type-reminder").val(),
                user_id: $("#event-user-id").val(),
                type_event: $("#event-type-event").val(),
                category: $("#event-category").val()
            };

            $.ajax({
                url: "calendar/create",
                type: 'GET',
                data: formData,
                success: function(response) {
                    // Handle success
                    $saveButton.html(originalButtonText).prop('disabled', false);
                    $this.$modal.modal('hide');

                    let dateEnd = formData.date_end ? formData.date_end : formData.date_start;

                    // Add the event to the calendar
                    $this.$calendarObj.addEvent({
                        title: formData.title,
                        start: formData.date_start,
                        end: dateEnd,
                        className: formData.category
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                var errorElement = $('.error-' + key);
                                if (errorElement.length) {
                                    errorElement.text(errors[key][0]);
                                }
                            }
                        }
                    } else {
                        console.error('Error:', error);
                    }
                    $saveButton.html(originalButtonText).prop('disabled', false);
                }
            });

        });

        // delete event when button is clicked
        $($this.$btnDeleteEvent.on('click', function(e) {
            if ($this.$selectedEvent) {
                $this.$selectedEvent.remove();
                $this.$selectedEvent = null;
                $this.$modal.modal('hide');
            }
        }));
    },

   //init CalendarApp
    $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

}(window.jQuery),

//initializing CalendarApp
function($) {
    "use strict";
    $.CalendarApp.init()
}(window.jQuery);
