<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_role_and_permission()
    {
        // ensure permission cache is cleared
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $user = User::factory()->create();

        $permission = Permission::create(['name' => 'edit articles' , 'display' => 'edit articles']);
        $role = Role::create(['name' => 'writer' , 'display' => 'writer']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('writer'));
        $this->assertTrue($user->hasPermissionTo('edit articles'));
        $this->assertTrue($user->can('edit articles'));
    }
}
