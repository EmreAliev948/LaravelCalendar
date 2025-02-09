<x-layout>
    <x-page-heading>Create event for {{$friend->name}}</x-page-heading>
    
    <x-forms.form method="POST" action="/friend/{{$friend->id}}/create-schedule">
        <x-forms.input label="Title" name="title" required />
        <x-forms.date label="Start Date" name="start" required />
        <x-forms.date label="End Date" name="end" required />
        <x-forms.input label="Description" name="description"/>
        <x-forms.color label="Event Color" name="color" />
        <x-forms.divider />
        <x-forms.button>Save</x-forms.button>
    </x-forms.form>
</x-layout> 