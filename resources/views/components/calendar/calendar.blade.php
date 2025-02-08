<div class="container mt-5">
    <div class="row">
        <div class="col-md-12"></div>
        <div class="container mx-auto px-4 py-6">
            <!-- Search and Controls Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Search Bar -->
                <div class="flex">
                    <div class="flex-1 flex">
                        <input type="text" id="searchInput"
                            class="flex-1 bg-gray-800 border border-gray-700 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-200 placeholder-gray-400 focus:bg-gray-700 focus:border-gray-600 focus:shadow-[0_0_0_0.2rem_rgba(255,255,255,0.1)]"
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
                        class="px-6 py-2 bg-[#2c3e50] hover:bg-[#34495e] text-white rounded-lg transition duration-150 ease-in-out flex items-center border border-[#34495e] hover:border-[#3d566e]">
                        <i class="bx bx-export mr-2"></i> {{__('Export Calendar')}}
                    </button>
                    <a href="{{ URL('add-schedule') }}"
                        class="px-6 py-2 bg-[#2c3e50] hover:bg-[#34495e] text-white rounded-lg transition duration-150 ease-in-out flex items-center border border-[#34495e] hover:border-[#3d566e]">
                        <i class="bx bx-plus mr-2"></i> {{__('Add Event')}}
                    </a>
                </div>
            </div>

            <!-- Calendar Container -->
            <div class="bg-[#1a1a1a] rounded-xl shadow-[0_0_10px_rgba(0,0,0,0.5)] overflow-hidden border border-[#333]">
                <div class="p-4">
                    <div id="calendar" class="min-h-[700px] bg-[#1a1a1a] [&_.fc]:bg-[#1a1a1a] [&_.fc]:text-white 
                        [&_.fc-theme-standard_td]:border-[#333] [&_.fc-theme-standard_th]:border-[#333]
                        [&_.fc-theme-standard_.fc-scrollgrid]:border-[#333] 
                        [&_.fc-day-today]:!bg-white/10
                        [&_.fc-button]:!bg-[#333] [&_.fc-button]:!border-[#444] [&_.fc-button]:!text-white 
                        [&_.fc-button:hover]:!bg-[#444]
                        [&_.fc-event]:bg-[#2c3e50] [&_.fc-event]:border-[#2c3e50] [&_.fc-event]:text-white
                        [&_.fc-toolbar-title]:text-white
                        [&_.fc-daygrid-day-number]:text-blue-600"></div>
                </div>
            </div>
        </div>
    </div>
</div>