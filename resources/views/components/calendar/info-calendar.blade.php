<div id="eventInfoPopup" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gradient-to-br from-[#2c3e50] to-[#34495e] p-8 rounded-xl shadow-2xl z-50 text-white min-w-[400px] max-w-[90vw] border border-[#4a6885]/30 backdrop-blur-sm">
    <div class="flex justify-between items-start mb-6">
        <h3 id="eventInfoTitle" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-300"></h3>
        <span id="eventOwnership" class="inline-block px-3 py-1 rounded-full text-xs font-semibold shadow-inner"></span>
    </div>
    
    <div class="space-y-4">
        <!-- Description Section -->
        <div class="bg-[#405d79] bg-opacity-50 p-4 rounded-lg backdrop-blur-md">
            <h4 class="text-sm uppercase tracking-wider text-gray-400 mb-2">Description</h4>
            <p id="eventInfoDescription" class="text-gray-200"></p>
        </div>

        <!-- Dates Section -->
        <div id="eventInfoDates" class="bg-[#405d79] bg-opacity-50 p-4 rounded-lg"></div>

        <!-- Details Section -->
        <div class="border-t border-[#4a6885] pt-4 space-y-2">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded" id="eventColor"></div>
                <span class="text-sm text-gray-400">Event Color</span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-400">Created by</p>
                    <p id="eventCreator" class="text-white font-medium"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Owner</p>
                    <p id="eventOwner" class="text-white font-medium"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-2">
                <div>
                    <p class="text-sm text-gray-400">Created</p>
                    <p id="eventCreatedAt" class="text-white text-sm"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Last updated</p>
                    <p id="eventUpdatedAt" class="text-white text-sm"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Section -->
    <div class="mt-6 flex justify-end space-x-3">
        <button id="closeEventInfo" class="px-4 py-2 rounded-lg bg-[#405d79] hover:bg-[#4a6885] transition-colors duration-200 text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Close
        </button>
    </div>
</div>