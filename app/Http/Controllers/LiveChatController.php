<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Events\MessageSent;
use App\Models\ChatMessage;


class LiveChatController extends Controller
{
    public function index()
    {

        $users = User::all()->where('id', '!=', auth()->id())->select('id', 'name', 'email');

        return Inertia::render('LiveChat/index',['users' => $users, 'currentuser_id' => auth()->id()]);
    }

    public function chat(User $user)
    {
        return ChatMessage::query()
        ->where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })
        ->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })
        ->with(['sender', 'receiver'])
        ->orderBy('id', 'asc')
        ->get();
    }

    public function send(User $user)
    {
        dd(564545);
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'text' => request()->input('message')
        ]);
     
        broadcast(new MessageSent($message));
     
        return $message;
    }
}
