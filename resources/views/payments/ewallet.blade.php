@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="glass-card text-center">
                    <div class="hero-badge d-inline-flex mb-3">Pembayaran E-Wallet</div>
                    <h2 class="fw-bolder mb-4">Pilih E-Wallet Anda</h2>
                    <p class="text-muted mb-4">Scan QR code atau buka aplikasi e-wallet untuk menyelesaikan pembayaran</p>

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

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg ewallet-option" data-provider="gopay">
                            <i class="bi bi-wallet2"></i> Bayar dengan GoPay
                        </button>
                        <button class="btn btn-outline-primary btn-lg ewallet-option" data-provider="shopeepay">
                            <i class="bi bi-wallet2"></i> Bayar dengan ShopeePay
                        </button>
                    </div>

                    <div id="instruction" class="mt-4"></div>

                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-secondary btn-lg w-100 mt-3">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.ewallet-option').forEach(btn => {
        btn.addEventListener('click', async () => {
            const provider = btn.dataset.provider;
            btn.disabled = true;
            btn.innerText = 'Mempersiapkan...';

            const res = await fetch('/payments/ewallet/initiate/{{ $transaction->id }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ provider })
            });
            const data = await res.json();
            if (data.ok) {
                document.getElementById('instruction').innerHTML = `<div class="alert alert-info">Silakan buka aplikasi ${provider.toUpperCase()} atau kunjungi <a href="${data.deeplink}">deeplink pembayaran</a>.</div><div class="mt-2"><button id="simulate" class="btn btn-success">Simulasikan Pembayaran (PAID)</button></div>`;
                document.getElementById('simulate').addEventListener('click', async () => {
                    await fetch('/payments/ewallet/webhook', { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}' }, body: JSON.stringify({ transaction_code: '{{ $transaction->transaction_code }}', status: 'PAID' }) });
                    // poll
                    checkStatus();
                });
            } else {
                document.getElementById('instruction').innerHTML = '<div class="alert alert-danger">Gagal memulai pembayaran.</div>';
            }
        });
    });

    async function checkStatus() {
        const res = await fetch('/payments/ewallet/status/{{ $transaction->id }}');
        const data = await res.json();
        if (data.status === 'paid') {
            window.location.href = '/transactions/{{ $transaction->id }}?paid=1';
        } else {
            setTimeout(checkStatus, 2000);
        }
    }
</script>
@endpush

@endsection
