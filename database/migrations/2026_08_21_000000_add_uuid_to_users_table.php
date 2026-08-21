<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid', 36)->nullable()->unique()->after('id');
        });

        // Populate existing users with UUIDs
        $users = DB::table('users')->whereNull('uuid')->get(['id']);
        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update(['uuid' => (string) Str::uuid()]);
        }

        // Attempt to make column non-nullable; ignore if doctrine/dbal not installed
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->string('uuid', 36)->unique()->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
