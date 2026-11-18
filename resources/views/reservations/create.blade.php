@extends('layouts.app')

@section('title', 'Pesan Lapangan - SM Sport Center')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col-lg-8">
            <span class="badge bg-success bg-opacity-25 text-success px-3 py-1 rounded-pill fw-semibold mb-2">
                <i class="bi bi-calendar2-check-fill me-1"></i> Reservasi Lapangan Online
            </span>
            <h1 class="h2 fw-bold text-white mb-1">Pilih Tanggal & Jam Lapangan</h1>
            <p class="text-white-50 mb-0">Pilih lapangan dan tanggal. Jam yang sudah dipesan otomatis terkunci untuk mencegah bentrok.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('fields.index') }}" class="btn btn-outline-light btn-sm px-3 py-2">
                <i class="bi bi-grid me-1"></i> Lihat Semua Lapangan
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Form Pemesanan -->
        <div class="col-xl-7">
            <div class="card p-4 shadow-lg" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                <h4 class="card-title text-white fw-bold mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                    <i class="bi bi-pencil-square text-success me-2"></i> Formulir Booking Lapangan
                </h4>

                <form action="{{ route('reservations.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <div class="row g-3">
                        <!-- Pilihan Lapangan -->
                        <div class="col-md-6">
                            <label for="field_id" class="form-label text-white small fw-semibold">Pilih Lapangan *</label>
                            <select name="field_id" id="field_id" class="form-select @error('field_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Lapangan --</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}" data-price="{{ $field->price_per_hour }}" @if(old('field_id', request('field_id')) == $field->id) selected @endif>
                                        {{ $field->name }} ({{ ucfirst($field->type) }}) - Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam
                                    </option>
                                @endforeach
                            </select>
                            @error('field_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilihan Tanggal -->
                        <div class="col-md-6">
                            <label for="reservation_date" class="form-label text-white small fw-semibold">Tanggal Bermain *</label>
                            <input type="date" name="reservation_date" id="reservation_date" class="form-control @error('reservation_date') is-invalid @enderror" value="{{ old('reservation_date', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                            @error('reservation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jam Mulai -->
                        <div class="col-md-6">
                            <label for="start_time" class="form-label text-white small fw-semibold">Jam Mulai *</label>
                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', '09:00') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jam Selesai -->
                        <div class="col-md-6">
                            <label for="end_time" class="form-label text-white small fw-semibold">Jam Selesai *</label>
                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', '10:00') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="col-12">
                            <label for="notes" class="form-label text-white small fw-semibold">Catatan Khusus (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" placeholder="Contoh: Sewa bola tambahan, request rompi, dll.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Warning Bentrok Waktu (Dinamis) -->
                    <div id="conflictWarning" class="alert alert-danger d-none mt-3 p-3 rounded-3" style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.5); color: #fca5a5;">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <strong>Jam Tidak Tersedia!</strong> Waktu yang Anda pilih bertabrakan dengan jam yang sudah dipesan. Silakan pilih jam kosong di panel jadwal sebelah kanan.
                    </div>

                    <!-- Kotak Estimasi Harga -->
                    <div class="p-3 rounded-3 mt-4 d-flex justify-content-between align-items-center" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.35);">
                        <div>
                            <small class="text-white-50 text-uppercase fw-semibold d-block">Estimasi Tarif</small>
                            <span class="small text-white">Durasi: <strong id="estimatedDuration">1</strong> Jam</span>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold fs-3" id="estimatedPrice" style="color: #4ade80;">Rp 0</div>
                        </div>
                    </div>

                    <!-- Tombol Lanjutkan -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-lg" id="submitBookingBtn" style="border-radius: 14px; background: linear-gradient(135deg, #16a34a, #22c55e); border: none;">
                            <span class="btn-text d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-calendar2-check-fill fs-5"></i> Buat Reservasi Sekarang
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span>Memproses Reservasi...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Ketersediaan Jam & Pengunci Jam Otomatis -->
        <div class="col-xl-5">
            <div class="card p-4 h-100 shadow-lg" style="background: #0f1d36; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                    <h5 class="card-title text-white fw-bold mb-0">
                        <i class="bi bi-clock-history text-success me-2"></i> Ketersediaan Jam Lapangan
                    </h5>
                </div>
                <p class="text-white-50 small mb-3">
                    Klik jam kosong untuk memilih langsung. Jam yang berwarna merah dengan gembok <i class="bi bi-lock-fill text-danger"></i> otomatis terkunci karena sudah dipesan.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1 small">
                        <i class="bi bi-check-circle-fill me-1"></i> Jam Kosong (Bisa Dipilih)
                    </span>
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-2 py-1 small">
                        <i class="bi bi-lock-fill me-1"></i> Terkunci (Sudah Dipesan)
                    </span>
                </div>

                <div id="scheduleGrid" class="d-grid gap-2" style="max-height: 480px; overflow-y: auto;">
                    <div class="text-white-50 small text-center py-4">Memuat jadwal slot jam...</div>
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
    const scheduleGrid = document.getElementById('scheduleGrid');
    const estimatedPrice = document.getElementById('estimatedPrice');
    const estimatedDuration = document.getElementById('estimatedDuration');
    const bookingForm = document.getElementById('bookingForm');
    const submitBookingBtn = document.getElementById('submitBookingBtn');
    const conflictWarning = document.getElementById('conflictWarning');

    let currentSlots = [];

    const formatRupiah = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);

    function checkSlotConflicts() {
        const start = startInput.value;
        const end = endInput.value;

        if (!start || !end || start >= end || currentSlots.length === 0) {
            conflictWarning.classList.add('d-none');
            submitBookingBtn.disabled = false;
            return false;
        }

        const startMin = parseInt(start.split(':')[0], 10) * 60 + parseInt(start.split(':')[1], 10);
        const endMin = parseInt(end.split(':')[0], 10) * 60 + parseInt(end.split(':')[1], 10);

        let hasConflict = false;

        currentSlots.forEach(slot => {
            if (slot.status !== 'available') {
                const slotStartMin = parseInt(slot.start.split(':')[0], 10) * 60 + parseInt(slot.start.split(':')[1], 10);
                const slotEndMin = parseInt(slot.end.split(':')[0], 10) * 60 + parseInt(slot.end.split(':')[1], 10);

                // Check overlap
                if (startMin < slotEndMin && endMin > slotStartMin) {
                    hasConflict = true;
                }
            }
        });

        if (hasConflict) {
            conflictWarning.classList.remove('d-none');
            submitBookingBtn.disabled = true;
        } else {
            conflictWarning.classList.add('d-none');
            submitBookingBtn.disabled = false;
        }

        return hasConflict;
    }

    function calculatePrice() {
        const selectedField = fieldSelect.options[fieldSelect.selectedIndex];
        const price = Number(selectedField?.dataset.price || 0);
        const start = startInput.value;
        const end = endInput.value;

        if (!start || !end || start >= end) {
            estimatedPrice.textContent = 'Rp 0';
            estimatedDuration.textContent = '0';
            return;
        }

        const startMin = parseInt(start.split(':')[0], 10) * 60 + parseInt(start.split(':')[1], 10);
        const endMin = parseInt(end.split(':')[0], 10) * 60 + parseInt(end.split(':')[1], 10);
        const duration = Math.max(0, (endMin - startMin) / 60);

        estimatedPrice.textContent = formatRupiah(duration * price);
        estimatedDuration.textContent = duration.toFixed(0);

        checkSlotConflicts();
    }

    function renderSchedule(slots) {
        currentSlots = slots;
        scheduleGrid.innerHTML = '';

        if (!slots || slots.length === 0) {
            scheduleGrid.innerHTML = '<div class="text-white-50 small text-center py-4">Tidak ada slot jam tersedia untuk tanggal ini.</div>';
            return;
        }

        slots.forEach(slot => {
            const isBooked = slot.status !== 'available';
            const isSelected = (startInput.value === slot.start && endInput.value === slot.end);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `btn btn-sm d-flex justify-content-between align-items-center py-2 px-3 rounded-3 text-start transition-all ${isBooked ? 'btn-outline-danger disabled' : (isSelected ? 'btn-success fw-bold' : 'btn-outline-success')}`;
            btn.style.opacity = isBooked ? '0.5' : '1';
            btn.disabled = isBooked;

            btn.innerHTML = `
                <span>
                    <i class="bi ${isBooked ? 'bi-lock-fill text-danger' : (isSelected ? 'bi-check2-circle text-white' : 'bi-clock text-success')} me-2"></i>
                    <strong>${slot.start} - ${slot.end} WIB</strong>
                </span>
                <span class="badge ${isBooked ? 'bg-danger' : (isSelected ? 'bg-white text-success' : 'bg-success')}">
                    ${isBooked ? 'Terkunci / Dipesan' : (isSelected ? 'Sedang Dipilih' : 'Tersedia')}
                </span>
            `;

            if (!isBooked) {
                btn.addEventListener('click', () => {
                    startInput.value = slot.start;
                    endInput.value = slot.end;
                    calculatePrice();
                    renderSchedule(currentSlots);
                });
            }

            scheduleGrid.appendChild(btn);
        });

        checkSlotConflicts();
    }

    async function loadSchedule() {
        const fieldId = fieldSelect.value;
        const date = dateInput.value;

        if (!fieldId || !date) {
            scheduleGrid.innerHTML = '<div class="text-white-50 small text-center py-4">Pilih lapangan dan tanggal untuk melihat slot jadwal.</div>';
            return;
        }

        scheduleGrid.innerHTML = '<div class="text-white-50 small text-center py-4"><span class="spinner-border spinner-border-sm me-2"></span>Memeriksa jadwal & mengunci jam terisi...</div>';

        try {
            const res = await fetch(`/reservations/schedule?field_id=${fieldId}&date=${date}`);
            const data = await res.json();
            renderSchedule(Array.isArray(data.slots) ? data.slots : []);
        } catch (err) {
            scheduleGrid.innerHTML = '<div class="text-danger small text-center py-4">Gagal memuat jadwal. Silakan refresh halaman.</div>';
        }
    }

    fieldSelect.addEventListener('change', () => { calculatePrice(); loadSchedule(); });
    dateInput.addEventListener('change', () => { loadSchedule(); });
    startInput.addEventListener('change', () => { calculatePrice(); });
    endInput.addEventListener('change', () => { calculatePrice(); });

    bookingForm.addEventListener('submit', function(e) {
        if (checkSlotConflicts()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Jam Terkunci!',
                text: 'Jam yang Anda pilih sudah dipesan oleh pelanggan lain. Silakan pilih jam kosong berwarna hijau.'
            });
            return;
        }

        submitBookingBtn.disabled = true;
        submitBookingBtn.querySelector('.btn-text').classList.add('d-none');
        submitBookingBtn.querySelector('.btn-loading').classList.remove('d-none');
    });

    if (fieldSelect.value) {
        loadSchedule();
        calculatePrice();
    }
</script>
@endpush
@endsection
