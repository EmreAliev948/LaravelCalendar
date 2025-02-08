<x-layout>
    <div class="space-y-10">
        <section class="text-center pt-6">
            <h1 class="font-bold text-4xl">Calendar</h1>
        </section>

        <section class="pt-10">
            
            @auth
            <x-layout-app/>    
            @endauth
            @guest
            <x-guest.hero></x-guest.hero>
            @endguest
            <div class="grid lg:grid-cols-3 gap-8 mt-6">
                

            </div>
        </section>

        <section>

            <div class="mt-6 space-x-1">
                
            </div>
        </section>

        <section>

            <div class="mt-6 space-y-6">
            
            </div>
        </section>
    </div>
</x-layout>