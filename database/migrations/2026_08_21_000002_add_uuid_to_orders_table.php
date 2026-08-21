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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('uuid', 36)->nullable()->unique()->after('id');
        });

        // Populate existing orders with UUIDs
        $orders = DB::table('orders')->whereNull('uuid')->get(['id']);
        foreach ($orders as $order) {
            DB::table('orders')->where('id', $order->id)->update(['uuid' => (string) Str::uuid()]);
        }

        // Attempt to make column non-nullable; ignore if doctrine/dbal not installed
        try {
            Schema::table('orders', function (Blueprint $table) {
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
