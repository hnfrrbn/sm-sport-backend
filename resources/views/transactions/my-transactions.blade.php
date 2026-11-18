@extends('layouts.app')

@section('title', 'Transaksi Saya')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-lg-8">
                <div class="hero-badge">Transaksi Saya</div>
                <h1 class="display-6 fw-bolder mb-1">Riwayat pembayaran dan invoice dalam satu tampilan</h1>
                <p class="text-muted mb-0">Lihat status pembayaran, cek rincian, dan akses invoice dengan cepat.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('customer.reservations') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-arrow-left"></i> Kembali ke Reservasi
                </a>
            </div>
        </div>

        <div class="glass-card">
            <div class="section-header-row">
                <h3 class="mb-0"><i class="bi bi-credit-card"></i> Daftar Transaksi</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Reservasi</th>
                            <th>Jumlah</th>
                            <th>Metode Bayar</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $key => $transaction)
                            <tr>
                                <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $key + 1 }}</td>
                                <td><strong>{{ $transaction->transaction_code }}</strong></td>
                                <td>{{ $transaction->reservation?->field->name ?? '-' }}</td>
                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($transaction->payment_method) }}</td>
                                <td>
                                    <span class="status-badge {{ $transaction->status === 'completed' || $transaction->status === 'paid' ? 'status-confirmed' : ($transaction->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->payment_date?->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        @if(in_array($transaction->status, ['paid', 'completed']))
                                            <a href="{{ route('transactions.invoice', $transaction) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-file-earmark-text"></i> Invoice
                                            </a>
                                        @endif
                                        @if($transaction->status === 'pending')
                                            <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan pembayaran ini?');">
                                                    <i class="bi bi-x-circle"></i> Batal Bayar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
