@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Pembayaran</h3>

    <div class="card p-4 text-center">
        <h5 class="mb-3">Silakan lakukan pembayaran ke rekening berikut:</h5>
        <p class="fw-bold">Bank BCA</p>
        <form action="{{ route('user.reservations.confirmPayment', $reservation->reservation_id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i> Sudah Bayar (Simulasi)
            </button>
        </form>
    </div>
</div>
@endsection
