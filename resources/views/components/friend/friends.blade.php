<x-layout>
    <x-calendar.calendar>{{ URL('/friend/' . $user->id . '/add-schedule') }}</x-calendar.calendar>
    <x-calendar.info-calendar></x-calendar.info-calendar>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div id='calendar'></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                firstDay: 1,
                events: '/friend/{{ $user->id }}/events',
                editable: true,
                timeZone: 'local',
                eventContent: function(info) {
                    var eventTitle = info.event.title;
                    var eventElement = document.createElement('div');
                    
                    // Always show delete button
                    var deleteButton = '<span style="cursor: pointer;" class="delete-event">❌</span> ';
                    eventElement.innerHTML = deleteButton + eventTitle;

                    eventElement.querySelector('.delete-event').addEventListener('click', function(e) {
                        e.preventDefault();
                        if (confirm("Are you sure you want to delete this event?")) {
                            var eventId = info.event.id;
                            fetch(`/friend/${eventId}/delete`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    info.event.remove();
                                } else {
                                    alert('Could not delete event');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error deleting event');
                            });
                        }
                    });
                    
                    return { domNodes: [eventElement] };
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                
                // Add drag and drop functionality
                eventDrop: function(info) {
                    var eventId = info.event.id;
                    var newStartDate = info.event.start;
                    var newEndDate = info.event.end || newStartDate;                        
                    newStartDate = new Date(newStartDate.getTime() + (24 * 60 * 60 * 1000));
                    newEndDate = new Date(newEndDate.getTime() + (24 * 60 * 60 * 1000));
                    
                    var newStartDateUTC = newStartDate.toISOString().slice(0, 10);
                    var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                    fetch(`/schedule/${eventId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            start_date: newStartDateUTC,
                            end_date: newEndDateUTC
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            info.revert();
                            throw new Error('Failed to update event');
                        }
                        console.log('Event moved successfully');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        info.revert();
                    });
                },

                // Add resize functionality
                eventResize: function(info) {
                    var eventId = info.event.id;
                    var newEndDate = info.event.end;
                    var newEndDateUTC = newEndDate.toISOString().slice(0, 10);

                    fetch(`/schedule/${eventId}/resize`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            end_date: newEndDateUTC
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            info.revert();
                            throw new Error('Failed to resize event');
                        }
                        console.log('Event resized successfully');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        info.revert();
                    });
                },

                // Add event click handler for info popup
                eventClick: function(info) {
                    var eventId = info.event.id;
                    $.ajax({
                        method: 'GET',
                        url: `/friend/${eventId}/info`,
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
                                .text(response.is_creator ? 'Created by You' : 'Friend\'s Event')
                                .removeClass('bg-emerald-500/50 bg-blue-500/50')
                                .addClass(response.is_creator ? 'bg-emerald-500/50' : 'bg-blue-500/50');
                            
                            $('#eventInfoPopup').removeClass('hidden').addClass('animate-fadeIn');
                        },
                        error: function(error) {
                            console.error('Error fetching event info:', error);
                        }
                    });
                }
            });

            // Add popup close handlers
            $('#closeEventInfo').on('click', function() {
                $('#eventInfoPopup').addClass('hidden');
            });

            $(window).on('click', function(event) {
                if (event.target == document.getElementById('eventInfoPopup')) {
                    $('#eventInfoPopup').addClass('hidden');
                }
            });

            calendar.render();
            
            // Debug: Check if events are being loaded
            fetch('/friend/{{ $user->id }}/events')
                .then(response => response.json())
                .then(data => {
                    console.log('Available events:', data);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                });
        });
    </script>
</x-layout>