<x-layout>
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
    </div>
</x-layout>