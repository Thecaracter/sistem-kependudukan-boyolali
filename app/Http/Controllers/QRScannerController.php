<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QRScannerController extends Controller
{
    public function index()
    {
        return view('pages.qr-scanner');
    }

    public function scan(Request $request)
    {
        try {
            $qrCode = $request->input('qr_code');
            return response()->json([
                'success' => true,
                'redirect_url' => route('identitas-rumah.show', $qrCode)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid'
            ], 400);
        }
    }
}
