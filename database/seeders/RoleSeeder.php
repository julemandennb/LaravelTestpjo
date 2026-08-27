<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        // custom, member, manager, admin, superadmin
        $customDashboard = Permission::firstOrCreate(
            ['name' => 'customDashboard'],
            ['display' => 'Custom dashboard']
        );

        // member, manager, admin, superadmin
        $memberItems = Permission::firstOrCreate(
            ['name' => 'memberItems'],
            ['display' => 'Member items']
        );

        // manager, admin, superadmin
        $backendDashboard = Permission::firstOrCreate(
            ['name' => 'backendDashboard'],
            ['display' => 'Backend dashboard']
        );

        $orderDashboard = Permission::firstOrCreate(
            ['name' => 'orderDashboard'],
            ['display' => 'Order dashboard']
        );

        $livechatDashboard = Permission::firstOrCreate(
            ['name' => 'livechatDashboard'],
            ['display' => 'Livechat dashboard']
        );

        $produktDashboard = Permission::firstOrCreate(
            ['name' => 'ProduktDashboard'],
            ['display' => 'Produkt dashboard']
        );

        $canGetAllOrderChat = Permission::firstOrCreate(
            ['name' => 'canGetAllOrderChat'],
            ['display' => 'Can get all order chat']
        );

        // admin, superadmin
        $activityLogDashboard = Permission::firstOrCreate(
            ['name' => 'activityLogDashboard'],
            ['display' => 'Activity log dashboard']
        );

        $userDashboard = Permission::firstOrCreate(
            ['name' => 'userDashboard'],
            ['display' => 'User dashboard']
        );

        $statisticsDashboard = Permission::firstOrCreate(
            ['name' => 'statisticsDashboard'],
            ['display' => 'Developer Dashboard']
        );

        //superadmin
        $serverHealth = Permission::firstOrCreate(
            ['name' => 'serverHealth'],
            ['display' => 'Server Health']
        );

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $roles = [
            'custom' => [
                'display' => 'Custom',
                'permissions' => [
                    $customDashboard,
                ],
            ],

            'member' => [
                'display' => 'Member',
                'permissions' => [
                    $customDashboard,
                    $memberItems,
                ],
            ],

            'manager' => [
                'display' => 'Manager',
                'permissions' => [
                    $customDashboard,
                    $memberItems,
                    $backendDashboard,
                    $orderDashboard,
                    $livechatDashboard,
                    $produktDashboard,
                    $canGetAllOrderChat,
                ],
            ],

            'admin' => [
                'display' => 'Admin',
                'permissions' => [
                    $customDashboard,
                    $memberItems,
                    $backendDashboard,
                    $orderDashboard,
                    $livechatDashboard,
                    $produktDashboard,
                    $activityLogDashboard,
                    $userDashboard,
                    $statisticsDashboard,
                    $canGetAllOrderChat,
                ],
            ],

            'superadmin' => [
                'display' => 'Super Admin',
                'permissions' => [
                    $customDashboard,
                    $memberItems,
                    $backendDashboard,
                    $orderDashboard,
                    $livechatDashboard,
                    $produktDashboard,
                    $activityLogDashboard,
                    $userDashboard,
                    $statisticsDashboard,
                    $serverHealth,
                    $canGetAllOrderChat,
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Create roles and assign permissions
        |--------------------------------------------------------------------------
        */

        foreach ($roles as $name => $data) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                ['display' => $data['display']]
            );

            $role->syncPermissions($data['permissions']);
        }
    }
}
