<x-layout>
    <div class="container mt-5">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">{{$user->name}}'s Calendar</h2>
            <a href="{{ route('friend.add-schedule', $user->id) }}" 
               class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-150 ease-in-out flex items-center">
                <i class="bx bx-plus mr-2"></i> {{__('Add Event')}}
            </a>
        </div>
        
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
                        eventElement.innerHTML = '<span style="cursor: pointer;">❌</span> ' + eventTitle;

                        eventElement.querySelector('span').addEventListener('click', function() {
                            if (confirm("Are you sure you want to delete this event?")) {
                                var eventId = info.event.id;
                                fetch(`/friend/${eventId}/delete`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    calendar.refetchEvents();
                                })
                                .catch(error => console.error('Error:', error));
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
    </div>
</x-layout>