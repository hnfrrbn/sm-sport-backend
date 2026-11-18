@extends('layouts.app')

@section('title', 'Input Booking Manual (Walk-in)')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-11">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="badge bg-success bg-opacity-25 text-success px-3 py-1 rounded-pill fw-semibold">
                        <i class="bi bi-person-walking me-1"></i> Pelanggan Datang Langsung (Walk-in)
                    </span>
                    <h2 class="h3 fw-bold text-white mb-1 mt-2">Input Booking Manual Kasir</h2>
                    <p class="text-white-50 mb-0">Catat pemesanan langsung dari kasir untuk pelanggan yang tidak memesan lewat aplikasi.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm px-3 py-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card p-4 shadow-lg" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                        <h5 class="card-title text-white fw-bold mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                            <i class="bi bi-pencil-square text-success me-2"></i> Formulir Pemesanan
                        </h5>

                        <form action="{{ route('admin.manual_booking.store') }}" method="POST" id="manualBookingForm">
                            @csrf

                            <!-- Data Pelanggan Walk-in -->
                            <div class="mb-4 p-3 rounded-3" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-info fw-bold small text-uppercase mb-3">
                                    <i class="bi bi-person-fill me-1"></i> Data Pelanggan
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="customer_name" class="form-label text-white small fw-semibold">Nama Pelanggan *</label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" placeholder="Contoh: Budi Santoso" value="{{ old('customer_name') }}" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="customer_phone" class="form-label text-white small fw-semibold">No. WhatsApp / HP *</label>
                                        <input type="text" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" placeholder="Contoh: 08123456789" value="{{ old('customer_phone') }}" required>
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pilihan Lapangan & Waktu -->
                            <div class="mb-4 p-3 rounded-3" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-success fw-bold small text-uppercase mb-3">
                                    <i class="bi bi-calendar-event me-1"></i> Lapangan & Jadwal Main
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="field_id" class="form-label text-white small fw-semibold">Pilih Lapangan *</label>
                                        <select name="field_id" id="field_id" class="form-select @error('field_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Lapangan --</option>
                                            @foreach($fields as $field)
                                                <option value="{{ $field->id }}" data-price="{{ $field->price_per_hour }}" @if(old('field_id') == $field->id) selected @endif>
                                                    {{ $field->name }} ({{ ucfirst($field->type) }}) - Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('field_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="reservation_date" class="form-label text-white small fw-semibold">Tanggal Main *</label>
                                        <input type="date" name="reservation_date" id="reservation_date" class="form-control @error('reservation_date') is-invalid @enderror" value="{{ old('reservation_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                        @error('reservation_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="start_time" class="form-label text-white small fw-semibold">Jam Mulai *</label>
                                        <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', '09:00') }}" required>
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="end_time" class="form-label text-white small fw-semibold">Jam Selesai *</label>
                                        <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', '10:00') }}" required>
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Pembayaran Kasir -->
                            <div class="mb-4 p-3 rounded-3" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-warning fw-bold small text-uppercase mb-3">
                                    <i class="bi bi-wallet2 me-1"></i> Pembayaran Kasir
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="payment_method" class="form-label text-white small fw-semibold">Metode Bayar *</label>
                                        <select name="payment_method" id="payment_method" class="form-select" required>
                                            <option value="cash" selected>Tunai / Cash di Kasir</option>
                                            <option value="qris">QRIS Kasir</option>
                                            <option value="transfer">Transfer Bank Langsung</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="payment_status" class="form-label text-white small fw-semibold">Status Pembayaran *</label>
                                        <select name="payment_status" id="payment_status" class="form-select" required>
                                            <option value="paid" selected>Lunas (Langsung Konfirmasi)</option>
                                            <option value="pending">Belum Lunas (Bayar Nanti)</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="notes" class="form-label text-white small fw-semibold">Catatan Kasir (Opsional)</label>
                                        <input type="text" name="notes" id="notes" class="form-control" placeholder="Contoh: Sewa rompi 1 set, DP 50%, dll.">
                                    </div>
                                </div>
                            </div>

                            <!-- Total Ringkasan Biaya -->
                            <div class="p-3 rounded-3 mb-4 d-flex justify-content-between align-items-center" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3);">
                                <div>
                                    <small class="text-white-50 text-uppercase fw-semibold d-block">Total Tarif Sewa</small>
                                    <span class="small text-white">Durasi: <strong id="durasiText">1</strong> Jam</span>
                                </div>
                                <div class="text-end">
                                    <span class="fs-3 fw-bold" id="totalHargaText" style="color: #4ade80;">Rp 0</span>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-lg" style="background: linear-gradient(135deg, #16a34a, #22c55e); border: none; border-radius: 14px;">
                                    <i class="bi bi-check-circle-fill me-2"></i> Simpan Booking & Buka Tiket
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Jadwal Interaktif & Kunci Jam -->
                <div class="col-lg-5">
                    <div class="card p-4 h-100 shadow-lg" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                        <h5 class="card-title text-white fw-bold mb-2">
                            <i class="bi bi-clock-history text-info me-2"></i> Ketersediaan Jam Lapangan
                        </h5>
                        <p class="text-white-50 small mb-3">
                            Slot jam yang berwarna <span class="badge bg-danger">Merah (Terkunci)</span> sudah dipesan oleh pelanggan lain. Klik jam hijau untuk memilih langsung.
                        </p>

                        <div class="d-flex gap-2 mb-3">
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1 small">
                                <i class="bi bi-check-circle"></i> Jam Kosong
                            </span>
                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-2 py-1 small">
                                <i class="bi bi-lock-fill"></i> Sudah Dipesan
                            </span>
                        </div>

                        <div id="scheduleSlots" class="d-grid gap-2" style="max-height: 480px; overflow-y: auto;">
                            <div class="text-white-50 small text-center py-4">Pilih lapangan dan tanggal untuk melihat slot jam.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const fieldSelect = document.getElementById('field_id');
    const dateInput = document.getElementById('reservation_date');
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');
    const durasiText = document.getElementById('durasiText');
    const totalHargaText = document.getElementById('totalHargaText');
    const scheduleSlots = document.getElementById('scheduleSlots');

    const formatRupiah = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

    function updatePrice() {
        const selectedField = fieldSelect.options[fieldSelect.selectedIndex];
        const price = Number(selectedField?.dataset.price || 0);
        const start = startInput.value;
        const end = endInput.value;

        if (!start || !end || start >= end) {
            durasiText.textContent = '0';
            totalHargaText.textContent = 'Rp 0';
            return;
        }

        const startMin = parseInt(start.split(':')[0], 10) * 60 + parseInt(start.split(':')[1], 10);
        const endMin = parseInt(end.split(':')[0], 10) * 60 + parseInt(end.split(':')[1], 10);
        const duration = Math.max(0, (endMin - startMin) / 60);

        durasiText.textContent = duration.toFixed(0);
        totalHargaText.textContent = formatRupiah(duration * price);
    }

    async function loadSlots() {
        const fieldId = fieldSelect.value;
        const date = dateInput.value;

        if (!fieldId || !date) {
            scheduleSlots.innerHTML = '<div class="text-white-50 small text-center py-4">Pilih lapangan dan tanggal untuk melihat slot jam.</div>';
            return;
        }

        scheduleSlots.innerHTML = '<div class="text-white-50 small text-center py-4"><span class="spinner-border spinner-border-sm me-2"></span>Memeriksa jadwal lapangan...</div>';

        try {
            const res = await fetch(`/reservations/schedule?field_id=${fieldId}&date=${date}`);
            const data = await res.json();

            scheduleSlots.innerHTML = '';

            if (!data.slots || data.slots.length === 0) {
                scheduleSlots.innerHTML = '<div class="text-white-50 small text-center py-4">Tidak ada slot jam tersedia.</div>';
                return;
            }

            data.slots.forEach(slot => {
                const isBooked = slot.status !== 'available';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `btn btn-sm d-flex justify-content-between align-items-center py-2 px-3 rounded-3 text-start ${isBooked ? 'btn-outline-danger disabled' : 'btn-outline-success'}`;
                btn.style.opacity = isBooked ? '0.5' : '1';
                btn.disabled = isBooked;

                btn.innerHTML = `
                    <span><i class="bi ${isBooked ? 'bi-lock-fill text-danger' : 'bi-clock text-success'} me-2"></i><strong>${slot.start} - ${slot.end} WIB</strong></span>
                    <span class="badge ${isBooked ? 'bg-danger' : 'bg-success'}">${isBooked ? 'Terkunci / Dipesan' : 'Tersedia'}</span>
                `;

                if (!isBooked) {
                    btn.addEventListener('click', () => {
                        startInput.value = slot.start;
                        endInput.value = slot.end;
                        updatePrice();
                    });
                }

                scheduleSlots.appendChild(btn);
            });
        } catch (err) {
            scheduleSlots.innerHTML = '<div class="text-danger small text-center py-4">Gagal memuat slot jam.</div>';
        }
    }

    fieldSelect.addEventListener('change', () => { updatePrice(); loadSlots(); });
    dateInput.addEventListener('change', loadSlots);
    startInput.addEventListener('change', updatePrice);
    endInput.addEventListener('change', updatePrice);

    if (fieldSelect.value) {
        loadSlots();
        updatePrice();
    }
</script>
@endpush
@endsection
