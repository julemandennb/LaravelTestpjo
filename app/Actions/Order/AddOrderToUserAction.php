<?php
namespace App\Actions\Order;

use App\Models\Order;
use App\Models\User;

class AddOrderToUserAction{

    public function execute(User $user):int
    {
        return Order::where('email', $user->email)
            ->where('user_id', 0)
            ->update([
                'user_id' => $user->id,
        ]);


    }

}
