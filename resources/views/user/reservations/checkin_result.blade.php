@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="alert alert-{{ $status == 'success' ? 'success' : ($status == 'info' ? 'info' : 'danger') }}">
            {{ $message }}
        </div>
        <a href="{{ route('user.reservations.index') }}" class="btn btn-primary mt-3">Kembali ke Daftar Reservasi</a>
    </div>
@endsection
