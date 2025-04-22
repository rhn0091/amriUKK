@extends('layouts.app_recepsionist')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 text-primary">Daftar Room</h2>
        </div>

        <div class="row g-4">
            @forelse ($rooms as $key => $room)
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $room->room_type }}</h5>
                            <p class="card-text">Jumlah Kamar: {{ $room->total_room }}</p>
                            <div class="mb-2">
                                @if($room->total_room > 0)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Tersedia ({{ $room->total_room}})
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Belum ada data room.
                    </div>
                </div>
            @endforelse
        </div>

    </div>
@endsection
