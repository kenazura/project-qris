<?php

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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('order_id')->unique();
        // foreignId menghubungkan ke tabel memberships
        $table->foreignId('membership_id')->constrained('memberships')->onDelete('cascade');
        $table->decimal('amount', 15, 2);
        $table->string('status'); // e.g., 'success', 'pending', 'failed'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
    
};
