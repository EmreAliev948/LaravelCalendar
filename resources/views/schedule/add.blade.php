<x-layout>
    <x-page-heading>Create a event</x-page-heading>
    @csrf
    <x-forms.form method="POST" action="/create-schedule">
        <x-forms.input label="Title" name="title" />
        <x-forms.date label="Start Date" name="start" />
        <x-forms.date label="Start Date" name="end" />
        <x-forms.input label="Description" name="description"/>
        <x-forms.color label="Event Color" name="color" />
        <x-forms.divider />
        <x-forms.button>Save</x-forms.button>
    </x-forms.form>
</x-layout>