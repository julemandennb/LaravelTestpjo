<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\updateProfilePasswordRequest;
use App\Http\Requests\Profile\updateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Shop/Customer/Profile', [
            'customer' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
        ]);
    }

    public function update(updateProfileRequest $request)
    {
        $user = $request->user();

        $validated = $request;

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(updateProfilePasswordRequest $request)
    {
        $validated = $request;

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
