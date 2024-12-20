<?php

use App\Models\User;
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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->text('text');
            $table->boolean('seen')->default(false);
            $table->foreignIdFor(User::class,'sender_id')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'receiver_id')->onDelete('cascade');

            //add index
            $table->index('sender_id');
            $table->index('receiver_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
