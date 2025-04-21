<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pemabayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 22px;
            font-weight: bold;
            color: #0d6efd;
        }

        .section-title {
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .details {
            margin-top: 20px;
        }

        .details .row {
            display: flex;
            justify-content: space-between;
        }

        .details .col {
            width: 48%;
        }

        .details .col ul {
            list-style-type: none;
            padding: 0;
        }

        .details .col ul li {
            margin-bottom: 10px;
        }

        .details .col ul li i {
            margin-right: 8px;
        }

        .total-payment {
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        .total-payment h4 {
            margin: 0;
            color: #0d6efd;
        }

        .total-payment p {
            margin-top: 5px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detail Reservasi</h1>
        </div>

        @php
            $checkIn = \Carbon\Carbon::parse($reservation->check_in_date);
            $checkOut = \Carbon\Carbon::parse($reservation->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $reservation->room->price * $reservation->total_rooms * $nights;
        @endphp

        <div class="details">
            {{-- Header Kamar --}}
            <div class="section-title">Informasi Kamar</div>
            <div class="row">
                <div class="col">
                    <ul>
                        <li><strong>Tipe Kamar:</strong> {{ $reservation->room->room_type }}</li>
                        <li><strong>Harga per Kamar:</strong> Rp {{ number_format($reservation->room->price, 0, ',', '.') }}</li>
                        <li><strong>Jumlah Kamar:</strong> {{ $reservation->total_rooms }}</li>
                        <li><strong>Kapasitas:</strong> {{ $reservation->room->capacity }} orang/kamar</li>
                        <li><strong>Durasi:</strong> {{ $nights }} malam</li>
                    </ul>
                </div>
                <div class="col">
                    <ul>
                        <li><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($reservation->check_in_date)->translatedFormat('l, d F Y') }}</li>
                        <li><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($reservation->check_out_date)->translatedFormat('l, d F Y') }}</li>
                    </ul>
                </div>
            </div>

            {{-- Total Pembayaran --}}
            <div class="total-payment">
                <h4>Total Pembayaran: Rp {{ number_format($totalPrice, 0, ',', '.') }}</h4>
                <p>Harga sudah termasuk pajak dan biaya layanan</p>
            </div>
        </div>
        <div>
            <p style="text-align: center; margin-top: 20px; font-size: 12px; color: #888;">Terima kasih telah memilih kami untuk menginap!</p>
            <p style="text-align: center; font-size: 12px; color: #888;">Jika ada pertanyaan, silakan hubungi kami di <a href="https://wa.me/6285751015164" style="color: #0d6efd;">WhatsApp</a>.</p>
        </div>
    </div>
</body>
</html>
