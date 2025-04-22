@extends('layouts.app')

@section('content')

    <div class="container">
        <h2>Riwayat Reservasi</h2>

        @if ($reservation->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Kamar</th>
                        <th>Tanggal Check-in</th>
                        <th>Tanggal Check-out</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservation as $reservation)
                        <tr>
                            <td>{{ $reservation->room->room_type }}</td>
                            <td>{{ $reservation->check_in_date }}</td>
                            <td>{{ $reservation->check_out_date }}</td>
                            <td>
                                <span class="badge bg-{{ $reservation->status === 'cancelled' ? 'danger' : 'success' }}">
                                    {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('reservations.rebook', $reservation->reservation_id) }}" class="btn btn-primary btn-sm">
                                    Pesan Lagi
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada riwayat reservasi.</p>
        @endif
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-5">
            <a href="{{ route('user.reservations.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Reservasi
            </a>
        </div>
    </div>

@endsection
