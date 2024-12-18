<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\DeleteATokenRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = User::find(auth()->user()->id);

        $tokens = $user->tokens->select("id","name","last_used_at","expires_at","created_at","updated_at");

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'tokens' => $tokens,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function makeAToken()
    {
        $user = User::find(auth()->user()->id);
        $token = $user->createToken("UserToken");
        return response()->json([
            'status' => 'success',
            'token' => $token->plainTextToken,
        ]);
    }

    public function deleteAToken(DeleteATokenRequest $request)
    {

        // If validation passes, you can access the validated data
        $validatedData = $request->validated();

        // Access the 'id' field from the validated data
        $id = $validatedData['id'];

        $user = User::find(auth()->user()->id);

        $user->tokens()->where('id', $id)->delete();

        return response()->noContent(200);

    }




}
