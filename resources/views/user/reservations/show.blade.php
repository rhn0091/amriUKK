@extends('layouts.app')

@section('content')
    @php
        $checkIn = \Carbon\Carbon::parse($reservation->check_in_date);
        $checkOut = \Carbon\Carbon::parse($reservation->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $reservation->room->price * $reservation->total_rooms * $nights;
    @endphp

    <div class="container mt-4">
        {{-- Header Kamar --}}
        <div class="position-relative mb-4 rounded shadow-sm" style="height: 200px; overflow: hidden;">
            @if ($reservation->room->images->first())
                <img src="{{ asset('storage/' . $reservation->room->images->first()->image_path) }}"
                    class="img-fluid w-100 h-100" style="object-fit: cover; max-height: 200px;" alt="Room Image">
                <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-50 w-100 p-3 text-white">
                    <h3 class="mb-0">{{ $reservation->room->room_type }}</h3>
                </div>
            @else
                <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center p-4">
                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        <h4 class="mt-2">{{ $reservation->room->room_type }}</h4>
                    </div>
                </div>
            @endif
        </div>

        {{-- Status Alert --}}
        @if ($reservation->status === 'pending')
            <div class="alert alert-warning alert-dismissible fade show mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Menunggu Konfirmasi!</strong> Reservasi Anda masih dalam proses verifikasi.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            {{-- Jika pending, user bisa upload bukti pembayaran --}}
            <form action="{{ route('user.reservations.confirmPayment', $reservation->reservation_id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Bukti Pembayaran</button>
            </form>
        @else
            <div class="alert alert-info alert-dismissible fade show mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Status Reservasi:</strong> {{ ucfirst($reservation->status) }}.
                <br>Jika ada perubahan, silakan hubungi resepsionis.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        {{-- Detail Reservasi --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold text-primary mb-4">
                    <i class="bi bi-card-checklist me-2"></i>Detail Reservasi
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="fw-bold text-muted mb-3">Informasi Kamar</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-door-open text-primary me-2"></i>
                                    <strong>Tipe Kamar:</strong> {{ $reservation->room->room_type }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-cash-coin text-success me-2"></i>
                                    <strong>Harga per Kamar:</strong> Rp
                                    {{ number_format($reservation->room->price, 0, ',', '.') }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-building text-secondary me-2"></i>
                                    <strong>Jumlah Kamar:</strong> {{ $reservation->total_rooms }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-people-fill text-warning me-2"></i>
                                    <strong>Kapasitas:</strong> {{ $reservation->room->capacity }} orang/kamar
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-moon-stars-fill text-info me-2"></i>
                                    <strong>Durasi:</strong> {{ $nights }} malam
                                </li>
                                <li>
                                    <i class="bi bi-calculator-fill text-danger me-2"></i>
                                    <strong>Total Harga:</strong> Rp {{ number_format($totalPrice, 0, ',', '.') }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="fw-bold text-muted mb-3">Detail Reservasi</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-calendar-check text-primary me-2"></i>
                                    <strong>Check-in:</strong>
                                    {{ \Carbon\Carbon::parse($reservation->check_in_date)->translatedFormat('l, d F Y') }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-calendar-x text-primary me-2"></i>
                                    <strong>Check-out:</strong>
                                    {{ \Carbon\Carbon::parse($reservation->check_out_date)->translatedFormat('l, d F Y') }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-clock-history text-info me-2"></i>
                                    <strong>Durasi:</strong> {{ $nights }} malam
                                </li>
                                <li>
                                    <i
                                        class="bi bi-patch-check {{ $reservation->status === 'paid' ? 'text-success' : 'text-warning' }} me-2"></i>
                                    <strong>Status:</strong>
                                    <span
                                        class="badge bg-{{ $reservation->status === 'pending' ? 'warning' : ($reservation->status === 'paid' ? 'success' : 'secondary') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Total Pembayaran --}}
                <div class="bg-primary bg-opacity-10 p-3 rounded mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-primary">Total Pembayaran</h6>
                        <h4 class="fw-bold mb-0 text-primary">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </h4>
                    </div>
                    <p class="small text-muted mb-0 mt-1">
                        <i class="bi bi-info-circle me-1"></i>
                        Harga sudah termasuk pajak dan biaya layanan
                    </p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-5">
            <a href="{{ route('user.reservations.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>

            <div class="d-flex gap-2">
                @if ($reservation->status === 'pending')
                    <form action="{{ route('user.reservations.update', $reservation->reservation_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-x-circle me-1"></i> Batalkan Reservasi
                        </button>
                    </form>
                @endif

                @if ($reservation->status === 'paid' || $reservation->status === 'checkin')
                    <a href="{{ route('user.reservations.pdf', $reservation->reservation_id) }}" target="_blank"
                        class="btn btn-primary">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
