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
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Tgl Pinjam</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Tgl Kembali Aktual</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Denda</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($rentals as $rental)
                    @php
                        $due = \Carbon\Carbon::parse($rental->tgl_kembali);
                        $actual = $rental->updated_at;
                        $days = 0;
                        $fine = 0;
                        if ($actual->gt($due)) {
                            $days = max(1, $actual->diffInDays($due));
                            $fine = $days * 50000;
                        }
                    @endphp
                    <tr>
                        <td class="px-4 py-2">{{ $rental->customer->nama_lengkap ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $rental->iphone->tipe_iphone ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $rental->tgl_pinjam }}</td>
                        <td class="px-4 py-2">{{ $actual->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">
                            @if ($fine > 0)
                                <span class="text-red-600 font-semibold">Rp {{ number_format($fine, 0, ',', '.') }} ({{ $days }} hari)</span>
                            @else
                                <span class="text-green-600">Tidak ada</span>
                            @endif
                        </td>
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
