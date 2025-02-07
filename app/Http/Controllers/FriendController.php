<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
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
       
    }
}
