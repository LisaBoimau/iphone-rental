<?php

namespace App\Http\Controllers;

use App\Models\Rental;

class HistoryController extends Controller
{
    public function index()
    {
        $rentals = Rental::where('status_rental', 'Selesai')
            ->with(['customer', 'iphone'])
            ->latest()
            ->get();

        return view('history.index', compact('rentals'));
    }
}
