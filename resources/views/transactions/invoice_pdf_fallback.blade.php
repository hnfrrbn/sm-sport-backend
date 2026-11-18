<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Invoice {{ $transaction->transaction_code }}</title>
</head>
<body>
    <div class="invoice-pdf-shell">
        <h2>Invoice / E-Ticket (PDF generator not available)</h2>
        <p>PDF generation package not installed. Silakan cetak halaman ini dari browser (Ctrl+P) atau pasang <code>barryvdh/laravel-dompdf</code>.</p>
        @include('transactions.invoice', ['transaction' => $transaction, 'reservation' => $reservation, 'user' => $user, 'qrUrl' => $qrUrl])
    </div>
</body>
</html>
