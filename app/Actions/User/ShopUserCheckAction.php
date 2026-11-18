<?php

namespace App\Actions\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ShopUserCheckAction
{
    /**
     * Create a new class instance.
     */
    public function execute()
    {

        $customDashboard = false;
        $memberItems = false;
        $backendDashboard = false;

        $user = Auth::user();
        if($user)
        {
            $customDashboard = $user->can('customDashboard');
            $memberItems = $user->can('memberItems');
            $backendDashboard = $user->can('backendDashboard');
        }


        return [

            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'customDashboard' => $customDashboard,
            'memberItems' => $memberItems,
            'backendDashboard' => $backendDashboard
        ];

    }
}
