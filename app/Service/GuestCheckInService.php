<?php

namespace App\Services;

use App\Jobs\SendGuestArrivedNotification;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuestCheckInService
{
    /**
     * Scan QR token and mark guest as arrived
     *
     * @param string $qr_token
     * @return Guest
     * @throws \Exception
     */
    public function scan(string $qr_token): Guest
    {
        return DB::transaction(function () use ($qr_token) {
            // Lock guest row to prevent race condition
            $guest = Guest::where('qr_token', $qr_token)->lockForUpdate()->firstOrFail();

            if ($guest->status) {
                throw new \RuntimeException('Guest already arrived');
            }

            // Generate unique random number
            do {
                $number = strtoupper(Str::random(8));
            } while (Guest::where('number', $number)->exists());

            // Update guest
            $guest->update([
                'status' => true,
                'arrived_at' => now(),
                'number' => $number,
            ]);

            $guest->refresh();

            // Dispatch notification job
            SendGuestArrivedNotification::dispatch($guest);

            return $guest;
        });
    }
}
