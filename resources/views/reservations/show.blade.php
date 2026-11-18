@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-calendar-check"></i> Detail Reservasi
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Lapangan:</strong> {{ $reservation->field->name }}</p>
                            <p><strong>Pelanggan:</strong> {{ $reservation->user->name }}</p>
                            <p><strong>Tanggal:</strong> {{ $reservation->reservation_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jam:</strong> {{ $reservation->start_time }} - {{ $reservation->end_time }}</p>
                            <p><strong>Durasi:</strong> {{ $reservation->duration_hours }} jam</p>
                            <p>
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $reservation->status === 'confirmed' ? 'success' : ($reservation->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong>Harga Total:</strong> Rp {{ number_format($reservation->total_price, 0, ',', '.') }}
                    </div>

                    @if($reservation->notes)
                        <div class="alert alert-secondary">
                            <strong>Catatan:</strong><br>
                            {{ $reservation->notes }}
                        </div>
                    @endif

                    @if($reservation->transaction)
                        <div class="alert alert-success" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac;">
                            <strong>Transaksi:</strong> {{ $reservation->transaction->transaction_code }}<br>
                            <strong>Status Pembayaran:</strong> {{ ucfirst($reservation->transaction->status) }}<br>
                            <strong>Tanggal Bayar:</strong> {{ $reservation->transaction->payment_date?->format('d M Y H:i') ?? '-' }}
                        </div>
                    @endif

                    @php $transaction = $reservation->transaction; @endphp

                    <!-- Tombol Cetak Struk / Tiket Booking -->
                    <div class="mb-4">
                        <a href="{{ route('reservations.ticket', $reservation->id) }}" class="btn btn-success btn-lg w-100 fw-bold shadow d-flex align-items-center justify-content-center gap-2" style="border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); border: none;">
                            <i class="bi bi-printer-fill fs-5"></i> Cetak Struk / Tiket Booking
                        </a>
                    </div>

                    <!-- Upload Bukti Pembayaran -->
                    <div class="card p-4 mb-4" style="background: #0b172a; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 18px;">
                        <h5 class="card-title text-white fw-bold mb-3">
                            <i class="bi bi-cloud-arrow-up-fill text-success me-2"></i> Bukti Pembayaran Transfer / QRIS
                        </h5>

                        @if($transaction && $transaction->payment_proof)
                            <div class="p-3 rounded-3 mb-3" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.25);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Bukti Bayar Terunggah</span>
                                    <small class="text-white-50">{{ $transaction->payment_proof_uploaded_at?->format('d M Y H:i') }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ asset($transaction->payment_proof) }}" target="_blank">
                                        <img src="{{ asset($transaction->payment_proof) }}" alt="Bukti Transfer" class="rounded border border-secondary" style="width: 70px; height: 70px; object-fit: cover;">
                                    </a>
                                    <div>
                                        <strong class="text-white d-block">Bukti transfer Anda telah tersimpan</strong>
                                        @if($transaction->status === 'paid' || $reservation->status === 'confirmed')
                                            <span class="text-success small fw-semibold"><i class="bi bi-check2-all"></i> Pembayaran telah diverifikasi & disetujui admin!</span>
                                        @else
                                            <span class="text-warning small fw-semibold"><i class="bi bi-hourglass-split"></i> Sedang menunggu verifikasi oleh admin.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($reservation->status !== 'cancelled' && (!$transaction || $transaction->status !== 'paid'))
                            <form action="{{ route('reservations.upload_proof', $reservation->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label text-white small fw-semibold">
                                        {{ ($transaction && $transaction->payment_proof) ? 'Ganti / Unggah Ulang Bukti Pembayaran:' : 'Pilih Foto Bukti Transfer / Pembayaran:' }}
                                    </label>
                                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*,.pdf" required>
                                    <small class="text-white-50 d-block mt-1">Format: JPG, PNG, atau PDF (Maksimal 5MB).</small>
                                </div>
                                <button type="submit" class="btn btn-primary fw-bold w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
                                    <i class="bi bi-upload"></i> Kirim Bukti Pembayaran
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        @php $transaction = $reservation->transaction; @endphp

                        @if($reservation->status !== 'cancelled')
                            @if($transaction && $transaction->status !== 'paid' && $transaction->status !== 'completed')
                                <div class="d-flex flex-column flex-md-row gap-2">
                                    <a href="{{ route('payments.qris', $transaction->id) }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-qr-code"></i> Bayar QRIS
                                    </a>
                                    <a href="{{ route('payments.ewallet', $transaction->id) }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-wallet2"></i> Bayar E-Wallet
                                    </a>
                                </div>
                            @elseif($transaction && $transaction->status === 'paid')
                                <div class="d-flex flex-column flex-md-row gap-2">
                                    <a href="{{ route('transactions.invoice', $transaction->id) }}" class="btn btn-success d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-file-earmark-text"></i> Lihat Invoice
                                    </a>
                                    <a href="{{ route('transactions.pdf', $transaction->id) }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-download"></i> Unduh PDF
                                    </a>
                                </div>
                            @elseif(!$transaction && in_array($reservation->status, ['pending', 'confirmed']))
                                <button id="create-transaction-btn" type="button" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2" data-reservation-id="{{ $reservation->id }}">
                                    <i class="bi bi-credit-card-2-front"></i> Buat Transaksi & Bayar
                                </button>
                            @endif

                            @if($transaction && $transaction->status === 'pending')
                                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-info d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-info-circle"></i> Cek Status Pembayaran
                                </a>
                            @endif

                            <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center gap-2 btn-open-cancel-modal"
                                data-reservation-id="{{ $reservation->id }}"
                                data-field-name="{{ $reservation->field->name }}"
                                data-date="{{ $reservation->reservation_date->format('d M Y') }}"
                                data-time="{{ $reservation->start_time }} - {{ $reservation->end_time }}">
                                <i class="bi bi-x-circle"></i> Batalkan Reservasi
                            </button>
                        @endif

                        <a href="{{ route('customer.reservations') }}" class="btn btn-secondary d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('create-transaction-btn')?.addEventListener('click', async function () {
        const button = this;
        const originalHtml = button.innerHTML;

        button.disabled = true;
        button.innerHTML = '<span class="me-2"><i class="bi bi-arrow-repeat"></i></span>Memproses...';

        try {
            const response = await fetch(`/reservations/${button.dataset.reservationId}/transaction`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();
            if (!response.ok || !data.ok) {
                throw new Error(data?.message || 'Gagal membuat transaksi. Silakan coba lagi.');
            }

            if (!data.transaction_id) {
                throw new Error('ID transaksi tidak ditemukan dari server.');
            }

            window.location.href = `/payments/qris/${data.transaction_id}`;
        } catch (error) {
            console.error(error);
            Swal.fire('Error', error.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    });
</script>

@endsection

@if(session('show_payment_modal'))
    @push('scripts')
    <script>
        // Payment buttons: ensure transaction exists then redirect
        async function ensureTransactionAndRedirect(reservationId, type) {
            try {
                const res = await fetch(`/reservations/${reservationId}/transaction`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const data = await res.json();
                if (!res.ok || !data.ok) {
                    const message = data?.message || 'Gagal membuat transaksi. Silakan coba lagi.';
                    console.error('createTransaction error', data);
                    Swal.fire('Error', message, 'error');
                    return;
                }

                const txId = data.transaction_id;
                if (!txId) {
                    Swal.fire('Error', 'ID transaksi tidak ditemukan dari server.', 'error');
                    return;
                }

                if (type === 'qris') {
                    window.location.href = `/payments/qris/${txId}`;
                } else {
                    window.location.href = `/payments/ewallet/${txId}`;
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
            }
        }

        document.getElementById('pay-qris-btn')?.addEventListener('click', function() {
            const id = this.dataset.reservationId;
            ensureTransactionAndRedirect(id, 'qris');
        });

        document.getElementById('pay-ewallet-btn')?.addEventListener('click', function() {
            const id = this.dataset.reservationId;
            ensureTransactionAndRedirect(id, 'ewallet');
        });
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '<strong>Reservasi Berhasil!</strong>',
                html: `
                    <div class="swal-payment-box">
                        <div>
                            <img src="/images/qris-logo.svg" class="swal-payment-img" alt="QRIS Logo" />
                        </div>
                        <div class="swal-payment-body">
                            <div class="swal-payment-header">Lapangan: {{ $reservation->field->name }}</div>
                            <div class="swal-payment-detail">{{ $reservation->reservation_date->format('d M Y') }} • {{ $reservation->start_time }} - {{ $reservation->end_time }}</div>
                            <div class="swal-payment-total"><strong>Rp {{ number_format($reservation->total_price,0,',','.') }}</strong></div>
                        </div>
                    </div>
                    <div class="swal-payment-detail mt-3">Pilih metode pembayaran:</div>
                    <div class="swal-payment-methods">
                        <button id="pay-qris" class="btn swal-payment-btn swal-payment-btn-primary"><i class="bi bi-qr-code me-2"></i> Bayar (QRIS)</button>
                        <button id="pay-ewallet" class="btn swal-payment-btn swal-payment-btn-secondary"><img src="/images/gopay.png" class="swal-payment-option-icon" alt="GoPay" /> Bayar (E-Wallet)</button>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Nanti',
                cancelButtonColor: '#6c757d',
                allowOutsideClick: false,
                width: 680,
                padding: '1.4rem',
                backdrop: true,
                customClass: {
                    popup: 'swal-custom-popup',
                    title: 'swal-custom-title',
                    content: 'swal-custom-content',
                    cancelButton: 'swal-cancel-btn'
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    popup.classList.add('swal-animate');

                    document.getElementById('pay-qris').addEventListener('click', function() {
                        window.location.href = '{{ route('payments.qris', session('show_payment_modal')) }}';
                    });

                    document.getElementById('pay-ewallet').addEventListener('click', function() {
                        window.location.href = '{{ route('payments.ewallet', session('show_payment_modal')) }}';
                    });
                }
            });
        });
    </script>
    @endpush
@endif
