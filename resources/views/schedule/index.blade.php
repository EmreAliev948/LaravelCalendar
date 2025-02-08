@extends('layouts.app')
@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Schedule Tracker</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .fc {
            background-color: #1a1a1a;
            color: #fff;
        }
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #333;
        }
        .fc-theme-standard .fc-scrollgrid {
            border-color: #333;
        }
        .fc-day-today {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        .fc-button {
            background-color: #333 !important;
            border-color: #444 !important;
            color: #fff !important;
        }
        .fc-button:hover {
            background-color: #444 !important;
        }
        .fc-event {
            background-color: #2c3e50;
            border-color: #2c3e50;
            color: white;
        }
        .fc-toolbar-title {
            color: #fff;
        }
        .card {
            background-color: #1a1a1a;
            border-color: #333;
        }
        
        /* Dark theme for input and buttons */
        .input-group .form-control {
            background-color: #2c2c2c;
            border-color: #444;
            color: #fff;
        }
        
        .input-group .form-control::placeholder {
            color: #888;
        }
        
        .input-group .form-control:focus {
            background-color: #333;
            border-color: #555;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
        }
        
        .btn-primary, .btn-success {
            background-color: #2c3e50 !important;
            border-color: #34495e !important;
            color: #fff !important;
        }
        
        .btn-primary:hover, .btn-success:hover {
            background-color: #34495e !important;
            border-color: #3d566e !important;
        }
        
        .btn-group .btn {
            margin-right: 5px;
        }
        
        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Search and Controls Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Search Bar -->
            <div class="flex">
                <div class="flex-1 flex">
                    <input type="text" 
                           id="searchInput" 
                           class="flex-1 bg-gray-800 border border-gray-700 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-200" 
                           placeholder="Search events...">
                    <button id="searchButton" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition duration-150 ease-in-out">
                        <i class="bx bx-search"></i> {{__('Search')}}
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <button id="exportButton" 
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-150 ease-in-out flex items-center">
                    <i class="bx bx-export mr-2"></i> {{__('Export Calendar')}}
                </button>
                <a href="{{ URL('add-schedule') }}" 
                   class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-150 ease-in-out flex items-center">
                    <i class="bx bx-plus mr-2"></i> {{__('Add Event')}}
                </a>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="bg-gray-900 rounded-xl shadow-2xl overflow-hidden">
            <div class="p-4">
                <div id="calendar" class="min-h-[700px] bg-gray-900"></div>
            </div>
        </div>
    </div>

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
            timeZone: 'UTC',
            events: '/events',
            editable: true,
            eventBackgroundColor: '#2c3e50',
            eventBorderColor: '#2c3e50',
            eventTextColor: '#fff',
            dayCellBackgroundColor: '#1a1a1a',

            // Deleting The Event
            eventContent: function(info) {
                var eventTitle = info.event.title;
                var eventElement = document.createElement('div');
                eventElement.innerHTML = '<span style="cursor: pointer;">❌</span> ' + eventTitle;

                eventElement.querySelector('span').addEventListener('click', function() {
                    if (confirm("Are you sure you want to delete this event?")) {
                        var eventId = info.event.id;
                        $.ajax({
                            method: 'get',
                            url: '/schedule/delete/' + eventId,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log('Event deleted successfully.');
                                calendar.refetchEvents(); // Refresh events after deletion
                            },
                            error: function(error) {
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

            eventDrop: function(info) {
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
                    success: function() {
                        console.log('Event moved successfully.');
                    },
                    error: function(error) {
                        console.error('Error moving event:', error);
                    }
                });
            },

            // Event Resizing
            eventResize: function(info) {
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
                    success: function() {
                        console.log('Event resized successfully.');
                    },
                    error: function(error) {
                        console.error('Error resizing event:', error);
                    }
                });
            },
        });

        calendar.render();

        document.getElementById('searchButton').addEventListener('click', function() {
            var searchKeywords = document.getElementById('searchInput').value.toLowerCase();
            filterAndDisplayEvents(searchKeywords);
        });


        function filterAndDisplayEvents(searchKeywords) {
            $.ajax({
                method: 'GET',
                url: `/events/search?title=${searchKeywords}`,
                success: function(response) {
                    calendar.removeAllEvents();
                    calendar.addEventSource(response);
                },
                error: function(error) {
                    console.error('Error searching events:', error);
                }
            });
        }


        // Exporting Function
        document.getElementById('exportButton').addEventListener('click', function() {
            var events = calendar.getEvents().map(function(event) {
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
@endsection