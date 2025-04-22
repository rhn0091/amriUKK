@extends('layouts.app_recepsionist')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Data Pemesanan kamar</h2>

        <form method="GET" action="{{ route('receptionist.dashboard') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Cari berdasarkan nama tamu...">
            </div>
            <div class="col-md-4">
                <input type="date" name="check_in_date" value="{{ request('check_in_date') }}" class="form-control"
                    placeholder="Filter tanggal check-in">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('receptionist.dashboard') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        {{-- Tabel Reservasi --}}
        <div class="card shadow-sm">
            <div class="card-body">
                @if ($reservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Tamu</th>
                                    <th>Tipe Kamar</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Total Kamar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->user->name ?? '-' }}</td>
                                        <td>{{ $reservation->room->room_type ?? '-' }}</td>
                                        <td>{{ $reservation->check_in_date }}</td>
                                        <td>{{ $reservation->check_out_date }}</td>
                                        <td>{{ $reservation->total_rooms }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $reservation->status === 'pending' ? 'warning' : ($reservation->status === 'confirmed' ? 'success' : 'secondary') }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editReservationModal{{ $reservation->id }}">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @foreach ($reservations as $reservation)
                            <div class="modal fade" id="editReservationModal{{ $reservation->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('reservations.update', $reservation->reservation_id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Reservasi -
                                                    {{ $reservation->user->name ?? '-' }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="pending"
                                                            {{ $reservation->status == 'pending' ? 'selected' : '' }}>
                                                            Pending</option>
                                                        <option value="paid"
                                                            {{ $reservation->status == 'paid' ? 'selected' : '' }}>
                                                            Paid</option>
                                                        <option value="checkin"
                                                            {{ $reservation->status == 'checkin' ? 'selected' : '' }}>
                                                            Checkin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                    </div>


                    <div class="mt-3">
                        {{ $reservations->withQueryString()->links() }}
                    </div>
                @else
                    <p class="text-muted">Tidak ada data reservasi ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
