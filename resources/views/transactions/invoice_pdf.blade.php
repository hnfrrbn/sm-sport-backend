<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Invoice {{ $transaction->transaction_code }}</title>
</head>
<body>
    <div class="invoice-pdf-shell">
        <div class="invoice-pdf-header">
            <div>
                <h2>Invoice / E-Ticket</h2>
                <div>No Invoice: <strong>{{ $transaction->transaction_code }}</strong></div>
                <div>Kode Reservasi: <strong>{{ $reservation->code ?? '-' }}</strong></div>
            </div>
            <div>
                <img src="{{ $qrUrl }}" alt="QR" class="invoice-qr" />
            </div>
        </div>

        <div class="invoice-pdf-details">
            <strong>Data Pelanggan</strong>
            <div>{{ $user->name ?? ($transaction->description ?? '-') }}</div>
            <div>{{ $user->email ?? '' }}</div>
            <div>{{ $user->phone ?? '' }}</div>

            <strong class="invoice-detail-title">Detail Reservasi</strong>
            <div>Lapangan: {{ $reservation->field->name ?? ($reservation->field_name ?? '-') }}</div>
            <div>Tanggal: {{ optional($reservation->start_time)->format('d M Y') ?? ($reservation->date ?? '-') }}</div>
            <div>Jam: {{ optional($reservation->start_time)->format('H:i') ?? '-' }} - {{ optional($reservation->end_time)->format('H:i') ?? '-' }}</div>
        </div>

        <table class="invoice-pdf-table">
            <thead>
                <tr>
                    <th class="invoice-pdf-th">Deskripsi</th>
                    <th class="invoice-pdf-th invoice-pdf-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="invoice-pdf-td">Biaya Lapangan</td>
                    <td class="invoice-pdf-td invoice-pdf-right">Rp {{ number_format($transaction->amount,0,',','.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="invoice-pdf-td invoice-pdf-right"><strong>Total Bayar</strong></td>
                    <td class="invoice-pdf-td invoice-pdf-right"><strong>Rp {{ number_format($transaction->amount,0,',','.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
