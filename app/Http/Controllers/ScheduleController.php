<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    //
    public function index()
    {
        return view('schedule.index');
    }

    public function create(Request $request)
    {
        $item = new Schedule();
        $item->user_id = auth()->id();
        $item->created_by = auth()->id();
        $item->title = $request->title;
        $item->start = $request->start;
        $item->end = $request->end;
        $item->description = $request->description;
        $item->color = $request->color;
        $item->save();

        return redirect('/');
    }


    public function getEvents()
    {
        $schedules = Schedule::where('user_id', auth()->id())->get();
        return response()->json($schedules);
    }

    public function deleteEvent($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'start' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
            'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
        ]);

        return response()->json(['message' => 'Event moved successfully']);
    }

    public function resize(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $newEndDate = Carbon::parse($request->input('end_date'))->setTimezone('UTC');
        $schedule->update(['end' => $newEndDate]);

        return response()->json(['message' => 'Event resized successfully.']);
    }

    public function search(Request $request)
    {
        $searchKeywords = $request->input('title');

        $matchingEvents = Schedule::where('user_id', auth()->id())
            ->where('title', 'like', '%' . $searchKeywords . '%')
            ->get();

        return response()->json($matchingEvents);
    }

    public function info($id)
    {
        $event = Schedule::with(['creator', 'owner'])->findOrFail($id);
        return response()->json([
            'title' => $event->title,
            'description' => $event->description,
            'start_date' => $event->start,
            'end_date' => $event->end,
            'color' => $event->color,
            'created_at' => $event->created_at->format('F j, Y g:i A'),
            'updated_at' => $event->updated_at->format('F j, Y g:i A'),
            'created_by' => $event->creator->name,
            'owner' => $event->owner->name,
            'is_owner' => $event->user_id === auth()->id()
        ]);
    }
}