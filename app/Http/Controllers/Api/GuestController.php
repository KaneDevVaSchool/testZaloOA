<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScanGuestRequest;
use App\Models\Guest;
use App\Services\GuestCheckInService;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GuestController extends Controller
{
    protected GuestCheckInService $checkInService;

    public function __construct(GuestCheckInService $checkInService)
    {
        $this->checkInService = $checkInService;
    }

    /**
     * Lấy thông tin guest theo QR token
     */
    public function showByQr(string $qr_token)
    {
        $guest = Guest::where('qr_token', $qr_token)->firstOrFail();

        return response()->json([
            'ok' => true,
            'guest' => $guest
        ]);
    }

    /**
     * Lấy thông tin check-in khách
     */
    public function checkInQRCode(string $qr_token)
    {
        $guest = Guest::where('qr_token', $qr_token)->firstOrFail();

        return response()->json([
            'ok' => true,
            'guest' => $guest
        ]);
    }

    /**
     * Scan QR và check-in
     */
    public function scanQr(ScanGuestRequest $request)
    {
        try {
            $guest = $this->checkInService->scan($request->validated()['qr_token']);

            return response()->json([
                'ok' => true,
                'guest' => $guest
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ], 409);
        }
    }

    /**
     * Sinh QR image (trả dưới dạng base64)
     */
    public function qrImage(string $qr_token)
    {
        $guest = Guest::where('qr_token', $qr_token)->firstOrFail();
        $url = route('api.guests.show', ['qr_token' => $guest->qr_token]);

        $svg = QrCode::size(300)->format('svg')->generate($url);
        $base64 = 'data:image/svg+xml;base64,' . base64_encode($svg);

        return response()->json([
            'ok' => true,
            'qr_code' => $base64
        ]);
    }

    /**
     * Lấy trạng thái guest (polling)
     */
    public function status(string $qr_token)
    {
        $guest = Guest::where('qr_token', $qr_token)->firstOrFail();

        return response()->json([
            'ok' => true,
            'status' => (bool)$guest->status,
            'number' => $guest->number,
            'full_name' => $guest->full_name,
            'phone' => $guest->phone,
        ]);
    }
}
