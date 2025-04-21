<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Receipt;
use App\Mail\SendReceiptQr;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index()
    {
        Reservation::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->whereDate('check_out_date', '<', now()->toDateString())
            ->update(['status' => 'checked_out']);

        $reservations = Reservation::where('user_id', Auth::id())
            ->with('room')
            ->orderBy('check_in_date', 'desc')
            ->paginate(10);

        return view('user.reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $rooms = Room::all();
        $selectedRoomId = $request->query('room_id');

        return view('user.reservations.create', compact('rooms', 'selectedRoomId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'rooms_id' => 'required|exists:rooms,rooms_id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $room = Room::where('rooms_id', $request->rooms_id)->firstOrFail();

        if ($room->total_room < $request->total_rooms) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jumlah kamar tidak mencukupi. Stok tersisa: ' . $room->total_room);
        }

        $reservation = null;
        $receipt = null;

        DB::transaction(function () use ($request, $room, &$reservation, &$receipt) {
            $reservation = Reservation::create([
                'reservation_id' => Str::uuid(),
                'user_id' => Auth::id(),
                'rooms_id' => $request->rooms_id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'total_rooms' => $request->total_rooms,
                'status' => 'pending',
            ]);

            // Kurangi stok kamar
            $room->decrement('total_room', $request->total_rooms);

            // Buat receipt
            $receipt = Receipt::create([
                'id' => Str::uuid(),
                'reservation_id' => $reservation->reservation_id,
                'receipt_code' => 'RCPT-' . strtoupper(Str::random(10)),
            ]);
        });

        if (!$reservation) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat reservasi. Silakan coba lagi.');
        }

        return redirect()->route('user.reservations.show', $reservation->reservation_id);
        // ->with('success', 'Pemesanan berhasil dibuat. QR Code sudah dikirim ke email kamu.')
        // ->with('qr_url', $qrUrl);
    }

    public function show($reservation_id)
    {
        $reservation = Reservation::with('room')->findOrFail($reservation_id);
        $receipt = Receipt::where('reservation_id', $reservation_id)->first();

        $qr_url = route('scan.qr.confirm', $receipt->id);

        return view('user.reservations.show', compact('reservation', 'receipt'));
    }


    public function pay($reservation_id)
    {
        $reservation = Reservation::with('room')->findOrFail($reservation_id);

        if ($reservation->status !== 'pending') {
            return redirect()->route('user.reservations.show', $reservation_id)->with('info', 'Reservasi sudah dibayar.');
        }

        $receipt = Receipt::where('reservation_id', $reservation_id)->firstOrFail();

        $qr_url = route('scan.qr.confirm', $receipt->id); 

        return view('user.reservations.pay', compact('reservation', 'receipt', 'qr_url'));
    }

    public function confirmPayment(Request $request, $reservation_id)
    {
        $reservation = Reservation::with('room')->where('reservation_id', $reservation_id)->firstOrFail();

        if ($reservation->status === 'pending') {
            $reservation->update(['status' => 'paid']);

            $qr_url = route('scan.qr.checkin', $reservation->reservation_id);
            Mail::to($reservation->user->email)->send(new SendReceiptQr($reservation, $qr_url));
        }

        return redirect()->route('user.reservations.show', $reservation_id)->with('success', 'Pembayaran berhasil! QR Check-in dikirim ke email.');
    }

    public function update(Request $request, $reservation_id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('reservation_id', $reservation_id)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,checked_out',
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->route('user.reservations.index')->with('success', 'Status pemesanan diperbarui.');
    }

    public function destroy($reservation_id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('reservation_id', $reservation_id)
            ->firstOrFail();

        $room = Room::where('rooms_id', $reservation->rooms_id)->first();
        if ($room) {
            $room->increment('total_room', $reservation->total_rooms);
        }

        $reservation->delete();

        return redirect()->route('user.reservations.index')->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function checkinViaQr($reservation_id)
    {
        $reservation = Reservation::where('reservation_id', $reservation_id)->firstOrFail();

        if ($reservation->status === 'paid') {
            $reservation->update(['status' => 'checkin']);
            return view('user.reservations.checkin_result', [
                'message' => 'Check-in berhasil!',
                'status' => 'success'
            ]);
        }

        if ($reservation->status === 'checkin') {
            return view('user.reservations.checkin_result', [
                'message' => 'Sudah check-in sebelumnya.',
                'status' => 'info'
            ]);
        }

        return view('user.reservations.checkin_result', [
            'message' => 'Reservasi belum dibayar atau tidak valid untuk check-in.',
            'status' => 'error'
        ]);
    }
    
    public function downloadPdf($id)
    {
        $reservation = Reservation::with('room.images')->findOrFail($id);
    
        $checkIn = \Carbon\Carbon::parse($reservation->check_in);
        $checkOut = \Carbon\Carbon::parse($reservation->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $reservation->room->price * $reservation->total_rooms * $nights;

        $pdf = Pdf::loadView('user.reservations.pdf', compact('reservation', 'checkIn', 'checkOut', 'nights', 'totalPrice'));

        return $pdf->download('reservasi-' . $reservation->reservation_id . '.pdf');
    }
}
