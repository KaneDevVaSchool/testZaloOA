<?php

namespace App\Http\Controllers;

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

    // Hiển thị thông tin khi quét QR
    public function showByQr(string $qr_token)
    {
        $guest = Guest::where('qr_token', $qr_token)->firstOrFail();
        return view('guests.qr', compact('guest'));
    }
    public function checkInQRCode(string $qr_checkin_cus)
    {
        $dataCheckinCus = Guest::where('qr_token', $qr_checkin_cus)->firstOrFail();
        return view('guests.checkin', compact('dataCheckinCus'));
    }

    // Scan QR và check-in
    public function scanQr(ScanGuestRequest $request)
    {
        try {
            $guest = $this->checkInService->scan($request->validated()['qr_token']);

            return response()->json([
                'ok' => true,
                'guest' => $guest,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 409);
        }
    }

    // Sinh QR image cho guest
    public function qrImage(string $qr_token)
    {
        $guest = Guest::where('qr_token', $qr_token)->firstOrFail();
        $url = route('guests.qr.show', ['qr_token' => $guest->qr_token]);

        $svg = QrCode::size(300)->format('svg')->generate($url);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
}
