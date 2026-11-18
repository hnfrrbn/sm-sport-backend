@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="hero-badge">Backend Transactions</div>
            <h1 class="h2 mb-1">
                <i class="bi bi-credit-card"></i> Daftar Transaksi
            </h1>
            <p class="text-muted mb-0">Pantau arus pembayaran dan status transaksi secara cepat dari panel admin.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="glass-card p-3 d-inline-block">
                <div class="small text-muted">Total pembayaran</div>
                <div class="fw-bold text-primary">Rp {{ number_format($transactions->total(), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Pelanggan</th>
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
                            <td>{{ optional($transaction->user)->name ?? 'Pengguna tidak tersedia' }}</td>
                            <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($transaction->payment_method) }}</td>
                            <td>
                                <span class="status-badge {{ in_array($transaction->status, ['completed', 'paid']) ? 'status-confirmed' : ($transaction->status === 'pending' ? 'status-pending' : 'status-cancelled') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>{{ $transaction->payment_date?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi</td>
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
@endsection
