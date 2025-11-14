<?php

namespace App\Services;

use App\Models\Guest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GuestCheckInService
{
    /**
     * Xử lý scan & check-in khách
     */
    public function scan(string $qrToken): Guest
    {
        return DB::transaction(function () use ($qrToken) {

            $guest = Guest::where('qr_token', $qrToken)->firstOrFail();

            // Đã check-in rồi → báo lỗi
            if ($guest->status) {
                throw new \RuntimeException("Khách đã check-in trước đó.");
            }

            // Sinh mã dự thưởng duy nhất
            $guest->number = $this->generateUniqueNumber();

            // Đánh dấu đã đến
            $guest->status = true;
            $guest->arrived_at = now();

            $guest->save();

            return $guest;
        });
    }

    /**
     * Sinh mã dự thưởng unique
     */
    private function generateUniqueNumber(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (Guest::where('number', $code)->exists());

        return $code;
    }
}
