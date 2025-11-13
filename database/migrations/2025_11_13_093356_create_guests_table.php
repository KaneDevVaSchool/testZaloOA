<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone')->index();
            $table->boolean('status')->default(false); // 0 = chưa đến, 1 = đã đến
            $table->string('number')->nullable()->unique(); // mã dự thưởng (nullable trước khi đến)
            $table->string('qr_token')->unique(); // token/chuỗi ký để QR (không dùng id nguyên)
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
