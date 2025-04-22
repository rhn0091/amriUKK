    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="{{ asset('css/checkin.css') }}">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <title>Check-in Result</title>
        <style>
            
        </style>
    </head>

    <body>
        <div class="container">
            <div class="alert alert-{{ $status == 'success' ? 'success' : ($status == 'info' ? 'info' : 'danger') }}">
                {{ $message }}
            </div>
            <a href="{{ route('user.reservations.index') }}" class="btn btn-primary mt-3">Kembali ke Daftar Reservasi</a>
        </div>
    </body>

    </html>
