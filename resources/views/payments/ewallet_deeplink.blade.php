@extends('layouts.app')

@section('content')
<div class="container invoice-page">
    <div class="card p-4 text-center">
        <h4>Instruksi Pembayaran - {{ strtoupper($provider) }}</h4>
        <p>{{ $instruction }}</p>
        <p>Jika ingin mensimulasikan callback, tekan tombol di bawah.</p>
        <div class="d-flex justify-content-center gap-2 mt-3">
            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-secondary">Kembali</a>
            <form method="POST" action="/payments/ewallet/webhook" class="d-inline">
                @csrf
                <input type="hidden" name="transaction_code" value="{{ $transaction->transaction_code }}" />
                <input type="hidden" name="status" value="PAID" />
                <button type="submit" class="btn btn-success">Simulasikan Pembayaran (PAID)</button>
            </form>
        </div>
    </div>
</div>
@endsection
