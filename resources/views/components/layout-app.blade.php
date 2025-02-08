
            <x-calendar />
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
            </script>