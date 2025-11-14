<?php

namespace App\Jobs;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendGuestArrivedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Guest $guest)
    {
        // guest sẽ được serialize (ID) rồi phục hồi khi job chạy
    }

    public function handle(): void
    {
        // Ví dụ: gửi thông báo qua Zalo/SMS/Email
        // $message = "Chào {$this->guest->full_name}, mã dự thưởng của bạn là {$this->guest->number}";
        // app(ZaloService::class)->send($this->guest->phone, $message);

        // Hoặc tạm thời log để kiểm tra
        Log::info('Guest arrived notification fired', [
            'guest_id' => $this->guest->id,
            'phone' => $this->guest->phone,
            'number' => $this->guest->number,
        ]);
    }
}
