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

        $users = User::all()->where('id', '!=', auth()->id())->select('uuid', 'name', 'email');

        return Inertia::render('LiveChat/index',['users' => $users, 'currentuser_id' => auth()->user()->uuid]);
    }

    public function chat(User $user)
    {
        $ChatMessages = ChatMessage::query()
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

        ChatMessage::query()
            ->where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('seen',0)
            ->update(['seen' => 1]);



        return $ChatMessages->map(fn (ChatMessage $message) => $this->formatMessage($message));
    }

    public function send(User $user)
    {
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'text' => request()->input('message')
        ]);

        broadcast(new MessageSent($message));

        return $this->formatMessage($message);
    }

    public function markAsSeen(ChatMessage $message)
    {
        abort_unless($message->receiver_id === auth()->id(), 403);

        $message->update(['seen' => true]);

        return response()->json(['seen' => true]);
    }

    private function formatMessage(ChatMessage $message): array
    {
        $message->loadMissing(['sender', 'receiver']);

        return [
            'id' => $message->id,
            'sender_id' => $message->sender->uuid,
            'receiver_id' => $message->receiver->uuid,
            'text' => $message->text,
        ];
    }
}
