<?php

namespace App\Http\Controllers;

use App\Models\Iphone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IphoneController extends Controller
{
    public function index()
    {
        $iphones = Iphone::latest()->get();

        return view('iphones.index', compact('iphones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_iphone' => 'required|string|max:255',
            'imei' => 'required|string|max:255|unique:iphones,imei',
            'status' => 'nullable|string|max:255',
        ]);

        $validated['status'] = $validated['status'] ?? 'Tersedia';

        do {
            $code = 'QR-' . $validated['imei'] . '-' . Str::random(6);
        } while (Iphone::where('qr_code_string', $code)->exists());

        $validated['qr_code_string'] = $code;

        Iphone::create($validated);

        return redirect('/iphones')->with('success', 'iPhone berhasil ditambahkan.');
    }

    public function update(Request $request, Iphone $iphone)
    {
        $validated = $request->validate([
            'tipe_iphone' => 'required|string|max:255',
            'imei' => 'required|string|max:255|unique:iphones,imei,' . $iphone->id,
        ]);

        $iphone->update($validated);

        return redirect('/iphones')->with('success', 'iPhone berhasil diperbarui.');
    }

    public function destroy(Iphone $iphone)
    {
        $iphone->delete();

        return redirect('/iphones')->with('success', 'iPhone berhasil dihapus.');
    }
}
