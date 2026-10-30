<?php

namespace App\Http\Controllers;

use App\Models\Rental;

class RiwayatController extends Controller
{
    public function index()
    {
        $rentals = Rental::where('status_rental', 'Selesai')
            ->with(['customer', 'iphone'])
            ->latest()
            ->get();

        return view('riwayat.index', compact('rentals'));
    }
}
