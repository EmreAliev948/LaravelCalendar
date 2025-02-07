<x-layout>
    @foreach ($friends as $friend)
        <p><a href="{{ route('friend.calendar', $friend->id) }}">{{$friend->name}}</a></p>
    @endforeach
</x-layout>