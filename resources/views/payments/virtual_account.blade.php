@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="glass-card text-center">
                    <div class="hero-badge d-inline-flex mb-3">Virtual Account</div>
                    <h2 class="fw-bolder mb-3">Pilih Bank Virtual Account</h2>
                    <p class="text-muted mb-4">Bayar melalui transfer virtual account BCA, Mandiri, atau BRI dengan nomor rekening virtual yang tertera.</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary btn-lg w-100 va-option" data-bank="bca">
                                <i class="bi bi-bank"></i><br>BCA
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary btn-lg w-100 va-option" data-bank="mandiri">
                                <i class="bi bi-bank"></i><br>Mandiri
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary btn-lg w-100 va-option" data-bank="bri">
                                <i class="bi bi-bank"></i><br>BRI
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="rounded-3 p-3 info-card">
                                <div class="text-muted small">Invoice</div>
                                <div class="fw-bold">{{ $transaction->transaction_code }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rounded-3 p-3 info-card">
                                <div class="text-muted small">Jumlah</div>
                                <div class="fw-bold text-primary">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div id="va-instruction" class="mt-4"></div>

                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-secondary btn-lg w-100 mt-3">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.va-option').forEach(btn => {
        btn.addEventListener('click', async () => {
            const bank = btn.dataset.bank;
            btn.disabled = true;
            btn.innerHTML = '<span class="me-2"><i class="bi bi-arrow-repeat"></i></span>Memproses...';

            const res = await fetch('/payments/va/initiate/{{ $transaction->id }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ bank })
            });
            const data = await res.json();

            if (data.ok) {
                document.getElementById('va-instruction').innerHTML = `
                    <div class="alert alert-info text-start">
                        <div class="fw-bold mb-2">Pembayaran via ${bank.toUpperCase()}</div>
                        <div>${data.instruction}</div>
                        <div class="mt-2"><strong>Nomor VA:</strong> ${data.va_number}</div>
                        <div class="mt-3">
                            <button id="simulate-va-paid" class="btn btn-success">Simulasikan Pembayaran (PAID)</button>
                        </div>
                    </div>
                `;

                document.getElementById('simulate-va-paid').addEventListener('click', async () => {
                    await fetch('/payments/va/webhook', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ transaction_code: '{{ $transaction->transaction_code }}', status: 'PAID' })
                    });
                    window.location.href = '/transactions/{{ $transaction->id }}?paid=1';
                });
            } else {
                document.getElementById('va-instruction').innerHTML = '<div class="alert alert-danger">Gagal memulai pembayaran Virtual Account.</div>';
            }
        });
    });
</script>
@endsection
