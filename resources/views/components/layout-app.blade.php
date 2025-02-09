<x-calendar>{{ URL('add-schedule') }}</x-calendar>
<x-calendar.info-calendar></x-calendar.info-calendar>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var calendarEl = document.getElementById('calendar');
    var events = [];
    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'standard',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        firstDay: 1,
        timeZone: 'UTC',
        events: '/events',
        editable: true,
        eventBackgroundColor: '#2c3e50',
        eventBorderColor: '#2c3e50',
        eventTextColor: '#fff',
        dayCellBackgroundColor: '#1a1a1a',

        // Deleting The Event
        eventContent: function (info) {
            var eventTitle = info.event.title;
            var eventElement = document.createElement('div');
            eventElement.innerHTML = '<span style="cursor: pointer;">❌</span> ' + eventTitle;

            eventElement.querySelector('span').addEventListener('click', function () {
                if (confirm("Are you sure you want to delete this event?")) {
                    var eventId = info.event.id;
                    $.ajax({
                        method: 'get',
                        url: '/schedule/delete/' + eventId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            console.log('Event deleted successfully.');
                            calendar.refetchEvents(); // Refresh events after deletion
                        },
                        error: function (error) {
                            console.error('Error deleting event:', error);
                        }
                    });
                }
            });
            return {
                domNodes: [eventElement]
            };
        },

        // Drag And Drop

        eventDrop: function (info) {
            var eventId = info.event.id;
            var newStartDate = info.event.start;
            var newEndDate = info.event.end || newStartDate;
            var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
            var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

            $.ajax({
                method: 'post',
                url: `/schedule/${eventId}`,
                data: {
                    '_token': "{{ csrf_token() }}",
                    start_date: newStartDateUTC,
                    end_date: newEndDateUTC,
                },
                success: function () {
                    console.log('Event moved successfully.');
                },
                error: function (error) {
                    console.error('Error moving event:', error);
                }
            });
        },

        // Event Resizing
        eventResize: function (info) {
            var eventId = info.event.id;
            var newEndDate = info.event.end;
            var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

            $.ajax({
                method: 'post',
                url: `/schedule/${eventId}/resize`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    end_date: newEndDateUTC
                },
                success: function () {
                    console.log('Event resized successfully.');
                },
                error: function (error) {
                    console.error('Error resizing event:', error);
                }
            });
        },
        eventInfo: function (info) {
            var eventTitle = info.event.title;
            var eventElement = document.createElement('div');
            eventElement.innerHTML = '<span style="cursor: pointer;">Info</span> ' + eventTitle;

            eventElement.querySelector('span').addEventListener('click', function () {
                if (confirm("Are you sure you want to delete this event?")) {
                    var eventId = info.event.id;
                    $.ajax({
                        method: 'get',
                        url: '/schedule/delete/' + eventId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            console.log('Event deleted successfully.');
                            calendar.refetchEvents();
                        },
                        error: function (error) {
                            console.error('Error deleting event:', error);
                        }
                    });
                }
            });
            return {
                domNodes: [eventElement]
            };
        },
        eventClick: function(info) {
            var eventId = info.event.id;
            $.ajax({
                method: 'GET',
                url: `/schedule/${eventId}/info`,
                success: function(response) {
                    $('#eventInfoTitle').text(response.title);
                    $('#eventInfoDescription').text(response.description || 'No description provided');
                    $('#eventInfoDates').html(`
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm uppercase tracking-wider text-gray-400 mb-2">Starts</h4>
                                <p class="text-white">${new Date(response.start_date).toLocaleString('en-US', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                            </div>
                            <div>
                                <h4 class="text-sm uppercase tracking-wider text-gray-400 mb-2">Ends</h4>
                                <p class="text-white">${new Date(response.end_date).toLocaleString('en-US', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                            </div>
                        </div>
                    `);
                    $('#eventColor').css('background-color', response.color);
                    $('#eventCreator').text(response.created_by);
                    $('#eventOwner').text(response.owner);
                    $('#eventCreatedAt').text(response.created_at);
                    $('#eventUpdatedAt').text(response.updated_at);
                    $('#eventOwnership')
                        .text(response.is_owner ? 'Your Event' : 'Shared Event')
                        .removeClass('bg-green-600 bg-blue-600')
                        .addClass(response.is_owner ? 'bg-emerald-500/50' : 'bg-blue-500/50');
                    
                    $('#eventInfoPopup').removeClass('hidden').addClass('animate-fadeIn');
                },
                error: function(error) {
                    console.error('Error fetching event info:', error);
                }
            });
        }
    });

    calendar.render();

    document.getElementById('searchButton').addEventListener('click', function () {
        var searchKeywords = document.getElementById('searchInput').value.toLowerCase();
        filterAndDisplayEvents(searchKeywords);
    });


    function filterAndDisplayEvents(searchKeywords) {
        $.ajax({
            method: 'GET',
            url: `/events/search?title=${searchKeywords}`,
            success: function (response) {
                calendar.removeAllEvents();
                calendar.addEventSource(response);
            },
            error: function (error) {
                console.error('Error searching events:', error);
            }
        });
    }


    // Exporting Function
    document.getElementById('exportButton').addEventListener('click', function () {
        var events = calendar.getEvents().map(function (event) {
            return {
                title: event.title,
                start: event.start ? event.start.toISOString() : null,
                end: event.end ? event.end.toISOString() : null,
                color: event.backgroundColor,
            };
        });

        var wb = XLSX.utils.book_new();

        var ws = XLSX.utils.json_to_sheet(events);

        XLSX.utils.book_append_sheet(wb, ws, 'Events');

        var arrayBuffer = XLSX.write(wb, {
            bookType: 'xlsx',
            type: 'array'
        });

        var blob = new Blob([arrayBuffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });

        var downloadLink = document.createElement('a');
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = 'events.xlsx';
        downloadLink.click();
    })

    // Add close button handler
    $('#closeEventInfo').on('click', function () {
        $('#eventInfoPopup').addClass('hidden');
    });

    // Close popup when clicking outside
    $(window).on('click', function (event) {
        if (event.target == document.getElementById('eventInfoPopup')) {
            $('#eventInfoPopup').addClass('hidden');
        }
    });
</script>