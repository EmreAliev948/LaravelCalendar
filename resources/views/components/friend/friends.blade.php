<x-layout>
        <x-calendar.calendar/>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

        <div id='calendar'></div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: '/friend/{{ $user->id }}/events',
                    editable: true,
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