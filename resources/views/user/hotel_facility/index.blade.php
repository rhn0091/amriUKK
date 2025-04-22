@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="text-center mb-5">Fasilitas Hotel Kami</h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($hotelFacilitys as $facility)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold">{{ $facility->facility_name }}</h5>
                            <p class="card-text text-muted">{{ $facility->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
