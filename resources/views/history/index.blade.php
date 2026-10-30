@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Riwayat Transaksi</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Customer</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">iPhone</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Pinjam</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Kembali</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($rentals as $rental)
                    <tr>
                        <td class="px-4 py-2">{{ $rental->customer->nama_lengkap ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $rental->iphone->tipe_iphone ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $rental->tgl_pinjam }}</td>
                        <td class="px-4 py-2">{{ $rental->tgl_kembali }}</td>
                        <td class="px-4 py-2">{{ $rental->status_rental }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">Belum ada riwayat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
