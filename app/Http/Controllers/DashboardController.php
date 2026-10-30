<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $rentals = Rental::where('status_rental', 'Aktif')
            ->with(['customer', 'iphone'])
            ->get();

        return view('dashboard', compact('rentals'));
    }
}
