@extends('layouts.app')

@section('title', 'Buat Lapangan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-plus-circle"></i> Tambah Lapangan Baru
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.fields.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lapangan</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Jenis Lapangan</label>
                                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="futsal" @if(old('type') === 'futsal') selected @endif>Futsal</option>
                                        <option value="badminton" @if(old('type') === 'badminton') selected @endif>Badminton</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Kapasitas Pemain</label>
                                    <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                                           value="{{ old('capacity') }}" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_per_hour" class="form-label">Harga per Jam (Rp)</label>
                                    <input type="number" name="price_per_hour" id="price_per_hour" class="form-control @error('price_per_hour') is-invalid @enderror" 
                                           value="{{ old('price_per_hour') }}" required step="1000">
                                    @error('price_per_hour')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="available" @if(old('status') === 'available') selected @endif>Tersedia</option>
                                        <option value="unavailable" @if(old('status') === 'unavailable') selected @endif>Tidak Tersedia</option>
                                        <option value="maintenance" @if(old('status') === 'maintenance') selected @endif>Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                            <a href="{{ route('admin.fields.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
