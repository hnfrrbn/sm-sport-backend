@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="glass-card text-center">
                    <div class="hero-badge d-inline-flex mb-3">Pembayaran QRIS</div>
                    <h2 class="fw-bolder mb-4">Pembayaran QRIS milik Anda</h2>
                    <p class="text-muted mb-4">Scan kode QR di bawah ini melalui aplikasi pembayaran favorit Anda. Transaksi aman, cepat, dan langsung terverifikasi.</p>

                    <div class="my-5 p-5 rounded-4 payment-banner">
                        <img src="{{ $qrUrl }}" alt="QRIS" class="img-fluid qr-preview" />
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="rounded-3 p-3 info-card">
                                <div class="text-muted small">Invoice Number</div>
                                <div class="fw-bold">{{ $transaction->transaction_code }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded-3 p-3 info-card">
                                <div class="text-muted small">Jumlah Pembayaran</div>
                                <div class="fw-bold text-primary">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Status: <span id="status" class="fw-bold">{{ $transaction->status }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button id="simulatePaid" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Simulasikan Pembayaran (PAID)
                        </button>
                        <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const transactionId = {{ $transaction->id }};

    async function checkStatus() {
        const res = await fetch('/payments/qris/status/' + transactionId);
        const data = await res.json();
        document.getElementById('status').innerText = data.status;
        if (data.status === 'paid') {
            window.location.href = '/transactions/' + transactionId + '?paid=1';
            return;
        }
        setTimeout(checkStatus, 3000);
    }

    document.getElementById('simulatePaid').addEventListener('click', async () => {
        await fetch('/payments/qris/webhook', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ transaction_code: '{{ $transaction->transaction_code }}', status: 'PAID' })
        });
        checkStatus();
    });

    checkStatus();
</script>
@endsection
