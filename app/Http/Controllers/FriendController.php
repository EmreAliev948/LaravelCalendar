<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FriendController extends Controller
{
    public function index(){
        $friends = User::select(['id', 'name'])->get();
        return view('friend', ['friends' => $friends]);

    }

    public function showCalendar(User $user)
    {
        return view('friends', ['user' => $user]);
    }

    public function create(Request $request)
    {
        $item = new Schedule();
        $item->user_id = auth()->id();
        $item->title = $request->title;
        $item->start = $request->start;
        $item->end = $request->end;
        $item->description = $request->description;
        $item->color = $request->color;
        $item->save();
        return redirect('/');
    }


    public function getEvents($userId)  // Remove optional parameter
    {
        $schedules = Schedule::where('user_id', $userId)->get();
        $events = $schedules->map(function($schedule) {
            return [
                'id' => $schedule->id,
                'title' => $schedule->title,
                'start' => Carbon::parse($schedule->start)->format('Y-m-d H:i:s'),
                'end' => Carbon::parse($schedule->end)->format('Y-m-d H:i:s'),
                'color' => $schedule->color ?? '#3788d8',
                'allDay' => false
            ];
        })->toArray();
        return response()->json($events);
    }

    public function deleteEvent($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'start' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
            'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
        ]);
    }

    public function resize(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $newEndDate = Carbon::parse($request->input('end_date'))->setTimezone('UTC');
        $schedule->update(['end' => $newEndDate]);
    }

    public function search(Request $request)
    {
        $searchKeywords = $request->input('title');
        $matchingEvents = Schedule::where('user_id', auth()->id())
            ->where('title', 'like', '%' . $searchKeywords . '%')
            ->get();

        return response()->json($matchingEvents);
    }

    public function addSchedule(User $user)
    {
        return view('schedule.friend-add', ['friend' => $user]);
    }

    public function createSchedule(Request $request, User $user)
    {
        $validated = $request->validate([
            'title' => 'required',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'description' => 'nullable',
            'color' => 'nullable'
        ]);

        $schedule = new Schedule();
        $schedule->user_id = $user->id;
        $schedule->created_by = auth()->id();
        $schedule->title = $validated['title'];
        $schedule->start = $validated['start'];
        $schedule->end = $validated['end'];
        $schedule->description = $validated['description'];
        $schedule->color = $validated['color'];
        $schedule->save();

        return redirect()->route('friend.calendar', $user->id);
    }
}
