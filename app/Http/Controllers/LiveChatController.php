<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;


class LiveChatController extends Controller
{
    public function index()
    {

        $users = User::all()->where('id', '!=', auth()->id())->select('id', 'name', 'email');

        return Inertia::render('LiveChat/index',['users' => $users]);
    }
}
