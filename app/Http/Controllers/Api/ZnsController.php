<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ZnsController extends Controller
{
    /**
     * Lấy danh sách khách để ZNS gửi invitation
     */
    public function listGuests()
    {
        // Lấy tất cả khách (có thể filter invited_at, status,... nếu muốn)
        $guests = Guest::with('department')->get();

        $data = $guests->map(function ($guest) {
            $qrUrl = route('api.guests.show', ['qr_token' => $guest->qr_token]);
            $svg = QrCode::size(300)->format('svg')->generate($qrUrl);
            $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($svg);

            return [
                'phone'      => $guest->phone,
                'full_name'  => $guest->full_name,
                'qr_token'   => $guest->qr_token,
                'qr_image'   => $qrBase64,
                'department' => [
                    'id'   => $guest->department->id ?? null,
                    'name' => $guest->department->department_name ?? null,
                ]
            ];
        });

        return response()->json([
            'ok'     => true,
            'guests' => $data,
        ]);
    }
}
