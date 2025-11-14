<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScanGuestRequest;
use App\Http\Resources\GuestResource;
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
     * Lấy thông tin guest theo QR
     */
    public function showByQr(string $qr_token)
    {
        $guest = $this->findGuest($qr_token);

        return response()->json([
            'ok'   => true,
            'data' => new GuestResource($guest)
        ]);
    }

    /**
     * Lấy thông tin dùng để check-in
     */
    public function checkInQRCode(string $qr_token)
    {
        $guest = $this->findGuest($qr_token);

        return response()->json([
            'ok'   => true,
            'data' => new GuestResource($guest)
        ]);
    }

    /**
     * Xử lý scan QR và check-in
     */
    public function scanQr(ScanGuestRequest $request)
    {
        try {
            $guest = $this->checkInService->scan($request->validated()['qr_token']);

            return response()->json([
                'ok'   => true,
                'data' => new GuestResource($guest->load('department'))
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'ok'      => false,
                'message' => $e->getMessage(),
            ], 409);
        }
    }

    /**
     * Sinh QR trả về base64
     */
    public function qrImage(string $qr_token)
    {
        $guest = $this->findGuest($qr_token);
        $url = route('api.guests.show', ['qr_token' => $guest->qr_token]);

        $svg = QrCode::size(300)->format('svg')->generate($url);

        return response()->json([
            'ok'       => true,
            'qr_code'  => 'data:image/svg+xml;base64,' . base64_encode($svg)
        ]);
    }

    /**
     * Polling trạng thái
     */
    public function status(string $qr_token)
    {
        $guest = $this->findGuest($qr_token);

        return response()->json([
            'ok'   => true,
            'data' => new GuestResource($guest)
        ]);
    }

    /**
     * Helper load guest + department
     */
    private function findGuest(string $qr_token): Guest
    {
        return Guest::with('department')
            ->where('qr_token', $qr_token)
            ->firstOrFail();
    }
}
