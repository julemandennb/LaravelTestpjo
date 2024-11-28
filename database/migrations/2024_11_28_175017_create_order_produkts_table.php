<?php

use App\Models\Order;
use App\Models\Produkt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_produkts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->onDelete('cascade');
            $table->foreignIdFor(Produkt::class)->onDelete('cascade');
            $table->integer('quantity'); // Store quantity of the product in the order
            $table->decimal('price', 8, 2); // Store price of the product at the time of order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_produkts');
    }
};
