<section class="bg-black dark:bg-black">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
        <a href="{{ route('login') }}" class="inline-flex justify-between items-center py-1 px-1 pr-4 mb-7 text-sm text-gray-300 bg-gray-800 rounded-full hover:bg-gray-700" role="alert">
            <span class="text-xs bg-primary-600 rounded-full text-white px-4 py-1.5 mr-3">New</span> 
            <span class="text-sm font-medium">Sign in to start scheduling!</span> 
            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        </a>
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">Connect, Schedule, and Meet with Ease</h1>
        <p class="mb-8 text-lg font-normal text-gray-400 lg:text-xl sm:px-16 xl:px-48">
            Our platform makes it simple to coordinate schedules with friends and organize meetups. Join thousands of users who are already making the most of their social life.
        </p>
        <div class="flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
            <a href="{{ route('register') }}" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900">
                Get Started
                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </a>
            <a href="{{ route('about') }}" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-gray-300 rounded-lg border border-gray-600 hover:bg-gray-800 focus:ring-4 focus:ring-gray-700">
                <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                Learn More
            </a>  
        </div>
        <div class="px-4 mx-auto text-center md:max-w-screen-md lg:max-w-screen-lg lg:px-36">
            <span class="font-semibold text-gray-500 uppercase">Trusted by users from</span>
            <div class="flex flex-wrap justify-center items-center mt-8 text-gray-400 sm:justify-between">
                <span class="mr-5 mb-5 lg:mb-0 hover:text-gray-300">
                    🏢 Companies
                </span>
                <span class="mr-5 mb-5 lg:mb-0 hover:text-gray-300">
                    🎓 Universities
                </span>
                <span class="mr-5 mb-5 lg:mb-0 hover:text-gray-300">
                    👥 Social Groups
                </span>
                <span class="mr-5 mb-5 lg:mb-0 hover:text-gray-300">
                    🌍 Worldwide
                </span>
            </div>
        </div> 
    </div>
</section>