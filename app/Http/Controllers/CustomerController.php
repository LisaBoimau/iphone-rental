<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_kontak' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_identitas' => 'required|string|max:255',
            'foto_ktp' => 'nullable|string|max:255',
        ]);

        Customer::create($validated);

        return redirect('/customers')->with('success', 'Customer berhasil ditambahkan.');
    }
}
