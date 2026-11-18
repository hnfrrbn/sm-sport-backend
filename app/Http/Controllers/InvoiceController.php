<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;

class InvoiceController extends Controller
{
    /**
     * Show the invoice / e-ticket view
     */
    public function show(Transaction $transaction)
    {
        $reservation = $transaction->reservation;
        $user = $transaction->user;

        // Build QR image URL using a public QR generation service (falls back if no QR package installed)
        $qrData = rawurlencode(sprintf('CHECKIN:%s', $transaction->transaction_code));
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$qrData}";

        return view('transactions.invoice', compact('transaction', 'reservation', 'user', 'qrUrl'));
    }

    /**
     * Generate PDF of the invoice (download or inline)
     */
    public function pdf(Request $request, Transaction $transaction)
    {
        $reservation = $transaction->reservation;
        $user = $transaction->user;

        $qrData = rawurlencode(sprintf('CHECKIN:%s', $transaction->transaction_code));
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$qrData}";

        $data = compact('transaction', 'reservation', 'user', 'qrUrl');

        // Prefer the DomPDF facade if available. Fall back to an HTML view when not installed.
        if (!class_exists(PdfFacade::class)) {
            return view('transactions.invoice_pdf_fallback', $data);
        }

        try {
            $pdf = PdfFacade::loadView('transactions.invoice_pdf', $data)->setPaper('a4', 'portrait');
        } catch (\Exception $e) {
            // If PDF rendering fails, fall back to HTML view with an informative message.
            return view('transactions.invoice_pdf_fallback', array_merge($data, ['pdf_error' => $e->getMessage()]));
        }

        $filename = sprintf('invoice_%s.pdf', $transaction->transaction_code);

        // Allow inline preview when `inline=1` is provided, otherwise force download.
        if ((string) $request->query('inline', '0') === '1') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }
}
