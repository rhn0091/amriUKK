@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Pembayaran</h3>

    <div class="card p-4">
        <h5 class="text-center mb-3">Silakan lakukan pembayaran ke rekening berikut:</h5>
        <div class="text-center mb-4">
            <p class="fw-bold mb-0">Bank BCA</p>
            <p>No Rek: 1234567890 a.n. Hotel Example</p>
        </div>

        <form action="{{ route('user.reservations.confirmPayment', $reservation->reservation_id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="payment_proof" class="form-label fw-bold">Upload Bukti Pembayaran (gambar)</label>
                <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-upload me-1"></i> Upload dan Konfirmasi Pembayaran
            </button>
        </form>
    </div>
</div>
@endsection
