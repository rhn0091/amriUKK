@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <a href="{{route('user.reservations.history')}}" class="btn btn-light border d-inline-flex align-items-center mb-4 shadow-sm">
            <i class="bi bi-clock-history me-2 text-primary"></i> <span class="text-primary fw-semibold">Lihat Riwayat Reservasi</span>
        </a>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @forelse ($reservations as $reservation)
            @if (in_array($reservation->status, ['cancelled', 'checked_out']))
                @continue
            @endif

            <div class="card shadow-sm border-0 mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $reservation->room->images->first()->image_path) }}"
                            class="img-fluid w-100" style="object-fit: cover; max-height: 250px;"
                            alt="{{ $reservation->room->room_type }}">
                    </div>

                    <div class="col-md-8 p-4 text-end">
                        <h5 class="card-title fw-semibold text-primary">{{ $reservation->room->room_type }}</h5>
                        <p class="mb-1"><strong>Check-in:</strong>
                            {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d M Y') }}</p>
                        <p class="mb-1"><strong>Check-out:</strong>
                            {{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d M Y') }}</p>
                        <p class="mb-1"><strong>Jumlah Kamar:</strong> {{ $reservation->total_rooms }}</p>
                        <p class="mb-2">
                            <strong>Status:</strong>
                            @php
                                $statusColor = match ($reservation->status) {
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'checked_in' => 'primary',
                                    'cancelled' => 'danger',
                                    'checked_out' => 'secondary',
                                    default => 'dark',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">
                                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                            </span>
                        </p>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('user.reservations.show', $reservation->reservation_id) }}"
                                class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-calendar-x fs-1 mb-3 d-block"></i>
                <p class="mb-0">Belum ada reservasi yang dibuat.</p>
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $reservations->links() }}
        </div>
    </div>
@endsection
