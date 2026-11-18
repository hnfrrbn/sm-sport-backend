@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row align-items-center mb-4">
            <div class="col-lg-8">
                <div class="hero-badge">Detail Transaksi</div>
                <h1 class="display-6 fw-bolder mb-1">{{ $transaction->transaction_code }}</h1>
                <p class="text-muted mb-0">Pantau status pembayaran dan akses invoice secara cepat dari satu halaman yang lebih rapi.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="status-badge {{ in_array($transaction->status, ['completed', 'paid']) ? 'status-confirmed' : ($transaction->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="glass-card premium-transaction-card">
                    <div class="section-header-row">
                        <h3 class="mb-0"><i class="bi bi-credit-card"></i> Informasi Transaksi</h3>
                    </div>
                    <div class="transaction-highlight">
                        <div>
                            <div class="text-muted small">Nilai Pembayaran</div>
                            <div class="fs-3 fw-bold text-primary">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Kode</div>
                            <div class="fw-semibold">{{ $transaction->transaction_code }}</div>
                        </div>
                    </div>
                    <div class="list-stack mt-3">
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light-subtle">
                            <div>
                                <small class="text-muted">Pelanggan</small><br>
                                <strong>{{ optional($transaction->user)->name ?? 'Pengguna tidak tersedia' }}</strong>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light-subtle">
                            <div>
                                <small class="text-muted">Metode Pembayaran</small><br>
                                <strong>{{ ucfirst($transaction->payment_method) }}</strong>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3">
                            <div>
                                <small class="text-muted">Tanggal Pembayaran</small><br>
                                <strong>{{ $transaction->payment_date?->format('d M Y H:i') ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                @if($transaction->reservation)
                    <div class="glass-card mt-4 premium-transaction-card">
                        <div class="section-header-row">
                            <h3 class="mb-0"><i class="bi bi-calendar-check"></i> Detail Reservasi</h3>
                        </div>
                        <div class="list-stack">
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light-subtle">
                                <div>
                                    <small class="text-muted">Lapangan</small><br>
                                    <strong>{{ $transaction->reservation->field->name }}</strong>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light-subtle">
                                <div>
                                    <small class="text-muted">Tanggal</small><br>
                                    <strong>{{ $transaction->reservation->reservation_date->format('d M Y') }}</strong>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <small class="text-muted">Jam</small><br>
                                    <strong>{{ $transaction->reservation->start_time }} - {{ $transaction->reservation->end_time }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-5">
                <div class="glass-card premium-transaction-card">
                    <div class="section-header-row">
                        <h3 class="mb-0"><i class="bi bi-cash-coin"></i> Tindakan Pembayaran</h3>
                    </div>
                    @if(!in_array($transaction->status, ['completed', 'paid']))
                        <!-- Form Upload Bukti Transfer -->
                        <div class="p-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1);">
                            <h6 class="text-white fw-bold small mb-2"><i class="bi bi-cloud-arrow-up text-success me-1"></i> Upload Bukti Transfer Bank:</h6>
                            @if($transaction->payment_proof)
                                <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded bg-success bg-opacity-10 border border-success border-opacity-25">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span class="text-white small">Bukti transfer sudah diunggah.</span>
                                    <a href="{{ asset($transaction->payment_proof) }}" target="_blank" class="ms-auto btn btn-xs btn-outline-light py-0 px-2 small">Lihat</a>
                                </div>
                            @endif
                            <form action="{{ route('transactions.upload_proof', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="payment_proof" class="form-control form-control-sm mb-2" accept="image/*,.pdf" required>
                                <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">
                                    <i class="bi bi-upload me-1"></i> Kirim Bukti Transfer
                                </button>
                            </form>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('payments.qris', $transaction->id) }}" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-qr-code"></i> Bayar dengan QRIS
                            </a>
                            <a href="{{ route('payments.va', $transaction->id) }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-bank"></i> Virtual Account (BCA / Mandiri / BRI)
                            </a>
                            <a href="{{ route('payments.ewallet', $transaction->id) }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-wallet2"></i> Bayar dengan E-Wallet
                            </a>
                        </div>
                    @else
                        <div class="alert alert-success mb-3" role="alert">
                            <i class="bi bi-check-circle"></i> Pembayaran telah dikonfirmasi
                        </div>
                    @endif

                    @if($transaction->reservation)
                        <div class="mb-3">
                            <a href="{{ route('reservations.ticket', $transaction->reservation->id) }}" class="btn btn-success btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2" target="_blank" style="border-radius: 12px;">
                                <i class="bi bi-printer-fill fs-5"></i> Cetak Tiket / Struk Booking
                            </a>
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        @if($transaction->status === 'pending')
                            <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" onsubmit="return confirm('Batalkan pembayaran ini?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-lg d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-x-circle"></i> Batalkan Pembayaran
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('transactions.invoice', $transaction->id) }}" class="btn btn-secondary btn-lg d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-file-earmark-text"></i> Lihat Invoice
                        </a>
                        <a href="{{ route('customer.transactions') }}" class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(request()->query('paid') == 1)
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrData = encodeURIComponent('CHECKIN:{{ $transaction->transaction_code }}');
            const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + qrData;

            Swal.fire({
                title: '<strong>Pembayaran Berhasil</strong>',
                html: `
                    <div class="swal-payment-box">
                        <div>
                            <img src="${qrUrl}" class="swal-payment-img" alt="QR Code" />
                        </div>
                        <div class="swal-payment-body">
                            <div class="swal-payment-header">Terima kasih, {{ optional($transaction->user)->name ?? 'Pelanggan' }}!</div>
                            <div class="swal-payment-detail">Transaksi <strong>{{ $transaction->transaction_code }}</strong> telah berhasil dibayar.</div>
                            <div class="swal-payment-total">Rp {{ number_format($transaction->amount,0,',','.') }}</div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Lihat Invoice',
                cancelButtonText: 'Tutup',
                showDenyButton: true,
                denyButtonText: 'Download PDF',
                width: 680,
                customClass: { popup: 'swal-custom-popup' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('transactions.invoice', $transaction->id) }}';
                } else if (result.isDenied) {
                    window.location.href = '{{ route('transactions.pdf', $transaction->id) }}';
                }
            });
        });
    </script>
    @endpush
@endif

@push('scripts')
<script>
    document.addEventListener('click', function(e) {
        const el = e.target.closest('.view-payload');
        if (!el) return;
        const payload = el.getAttribute('data-payload');
        let pretty = '';
        try { pretty = JSON.stringify(JSON.parse(payload), null, 2); } catch (err) { pretty = payload; }
        Swal.fire({
            title: 'Payload Attempt',
            html: `<pre class="swal-json-pre">${Swal.escapeHtml(pretty)}</pre>`,
            width: 760
        });
    });
</script>
@endpush

@endsection
