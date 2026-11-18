@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row align-items-center mb-4">
            <div class="col-lg-8">
                <div class="hero-badge">Invoice / E-Ticket</div>
                <h1 class="display-6 fw-bolder mb-1">{{ $transaction->transaction_code }}</h1>
                <p class="text-muted mb-0">Bukti pembayaran dan detail reservasi lapangan Anda.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="status-badge {{ in_array($transaction->status, ['completed', 'paid']) ? 'status-confirmed' : ($transaction->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>

        <div class="glass-card">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="text-muted small">No Invoice</div>
                        <div class="fw-bold">{{ $transaction->transaction_code }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <div class="text-muted small">Metode Pembayaran</div>
                        <div class="fw-bold">{{ ucfirst($transaction->payment_method ?? 'N/A') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item text-center">
                        @if($qrUrl)
                            <img src="{{ $qrUrl }}" alt="QR Code" class="invoice-qr" />
                        @endif
                    </div>
                </div>
            </div>

            @if(($reservation && $reservation->status === 'cancelled') || ($transaction && $transaction->status === 'cancelled'))
                <div class="alert alert-danger d-flex align-items-center gap-3" role="alert">
                    <div>
                        <span class="badge bg-danger text-white">DIBATALKAN</span>
                    </div>
                    <div>
                        <strong>Reservasi dibatalkan.</strong>
                        @if($reservation && $reservation->cancellation_reason)
                            <div class="small">Alasan: {{ $reservation->cancellation_reason }}</div>
                        @endif
                    </div>
                </div>
            @endif

            <hr class="my-4">

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3"><i class="bi bi-person-circle"></i> Data Pelanggan</h5>
                    <div class="info-item">
                        <div class="text-muted small">Nama</div>
                        <div class="fw-bold">{{ $user->name ?? ($transaction->description ?? '-') }}</div>
                    </div>
                    <div class="info-item mt-2">
                        <div class="text-muted small">Email</div>
                        <div>{{ $user->email ?? '-' }}</div>
                    </div>
                    <div class="info-item mt-2">
                        <div class="text-muted small">Telepon</div>
                        <div>{{ $user->phone ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check"></i> Detail Reservasi</h5>
                    <div class="info-item">
                        <div class="text-muted small">Lapangan</div>
                        <div class="fw-bold">{{ $reservation->field->name ?? ($reservation->field_name ?? '-') }}</div>
                    </div>
                    <div class="info-item mt-2">
                        <div class="text-muted small">Tanggal</div>
                        <div>{{ $reservation->reservation_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="info-item mt-2">
                        <div class="text-muted small">Jam</div>
                        <div>{{ $reservation->start_time ?? '-' }} - {{ $reservation->end_time ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold mb-3"><i class="bi bi-receipt"></i> Rincian Biaya</h5>
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th class="fw-bold">Deskripsi</th>
                        <th class="fw-bold text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Biaya Lapangan</td>
                        <td class="text-end">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Pajak / Biaya Lainnya</td>
                        <td class="text-end">-</td>
                    </tr>
                    <tr class="border-top border-primary-subtle">
                        <td class="fw-bold">Total Bayar</td>
                        <td class="text-end fw-bold text-primary invoice-total-amount">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <hr class="my-4">

            <div class="d-grid gap-2">
                <a href="{{ route('transactions.pdf', $transaction->id) }}" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-download"></i> Unduh PDF
                </a>
                <button class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center gap-2" onclick="window.print()" type="button">
                    <i class="bi bi-printer"></i> Cetak Bukti Pembayaran
                </button>
                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
