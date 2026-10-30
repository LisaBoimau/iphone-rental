<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Iphone;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function scanBorrow()
    {
        $customers = Customer::orderBy('nama_lengkap')->get();
        $iphones = Iphone::where('status', 'Tersedia')->orderBy('tipe_iphone')->get();

        return view('rental.scan-borrow', compact('customers', 'iphones'));
    }

    public function lookupIphone(Request $request)
    {
        $code = $request->query('code');
        $iphone = Iphone::where('qr_code_string', $code)->orWhere('imei', $code)->first();

        if (! $iphone) {
            return response()->json(['success' => false, 'message' => 'Unit tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'iphone' => [
                'id' => $iphone->id,
                'tipe_iphone' => $iphone->tipe_iphone,
                'imei' => $iphone->imei,
                'status' => $iphone->status,
            ],
        ]);
    }

    public function storeBorrow(Request $request)
    {
        $request->validate([
            'iphone_id' => 'required|exists:iphones,id',
            'durasi_jam' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:customers,id',
            'nama_lengkap' => 'required_without:customer_id|nullable|string|max:255',
            'no_kontak' => 'required_without:customer_id|nullable|string|max:255',
            'alamat' => 'required_without:customer_id|nullable|string',
            'no_identitas' => 'required_without:customer_id|nullable|string|max:255',
            'foto_ktp' => 'nullable|string|max:255',
        ]);

        $iphone = Iphone::findOrFail($request->iphone_id);

        if ($iphone->status !== 'Tersedia') {
            return back()->withErrors(['iphone' => 'Unit tidak tersedia.'])->withInput();
        }

        if ($request->filled('customer_id')) {
            $customer = Customer::findOrFail($request->customer_id);
        } else {
            $customer = Customer::create([
                'nama_lengkap' => $request->nama_lengkap,
                'no_kontak' => $request->no_kontak,
                'alamat' => $request->alamat,
                'no_identitas' => $request->no_identitas,
                'foto_ktp' => $request->foto_ktp,
            ]);
        }

        $tgl_pinjam = now();
        $tgl_kembali = $tgl_pinjam->copy()->addHours((int) $request->durasi_jam);

        Rental::create([
            'customer_id' => $customer->id,
            'iphone_id' => $iphone->id,
            'tgl_pinjam' => $tgl_pinjam,
            'durasi_jam' => (int) $request->durasi_jam,
            'tgl_kembali' => $tgl_kembali,
            'status_rental' => 'Aktif',
        ]);

        $iphone->update(['status' => 'Dipinjam']);

        return redirect('/dashboard')->with('success', 'Peminjaman berhasil dimulai.');
    }

    public function scanReturn(Rental $rental)
    {
        $rental->load(['customer', 'iphone']);

        return view('rental.scan-return', compact('rental'));
    }

    public function completeReturn(Request $request, Rental $rental)
    {
        $rental->load(['customer', 'iphone']);

        if ($request->filled('manual_code')) {
            $code = trim((string) $request->manual_code);
            if ($code !== $rental->iphone->qr_code_string && $code !== $rental->iphone->imei) {
                return back()->withErrors(['manual_code' => 'IMEI/QR tidak cocok dengan unit.'])->withInput();
            }
        }

        $due = \Carbon\Carbon::parse($rental->tgl_kembali);
        $now = now();
        $days = 0;
        $fine = 0;
        if ($now->gt($due)) {
            $days = max(1, $now->diffInDays($due));
            $fine = $days * 50000;
        }

        $rental->update(['status_rental' => 'Selesai']);
        $rental->iphone()->update(['status' => 'Tersedia']);

        $message = 'Peminjaman selesai.';
        if ($fine > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($fine, 0, ',', '.') . ' (' . $days . ' hari).';
        }

        return redirect('/riwayat')->with('success', $message);
    }
}
