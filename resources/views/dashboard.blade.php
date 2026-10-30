@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Penyewaan Aktif</h2>
        <a href="{{ url('/rental/scan-borrow') }}" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">+ Tambah Peminjaman</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nama Customer</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Tipe iPhone</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Waktu Pinjam</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Sisa Waktu / Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($rentals as $rental)
                    <tr>
                        <td class="px-4 py-2">
                            <button type="button"
                                class="text-blue-600 underline customer-modal-btn"
                                data-nama="{{ $rental->customer->nama_lengkap ?? '-' }}"
                                data-kontak="{{ $rental->customer->no_kontak ?? '-' }}"
                                data-alamat="{{ $rental->customer->alamat ?? '-' }}"
                                data-ktp="{{ $rental->customer->no_identitas ?? '-' }}"
                                data-foto="{{ $rental->customer->foto_ktp ?? '' }}">
                                {{ $rental->customer->nama_lengkap ?? '-' }}
                            </button>
                        </td>
                        <td class="px-4 py-2">{{ $rental->iphone->tipe_iphone ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $rental->tgl_pinjam }}</td>
                        <td class="px-4 py-2">{{ $rental->status_rental }}</td>
                        <td class="px-4 py-2 timer-cell" data-kembali="{{ $rental->tgl_kembali->toISOString() }}" data-rental-id="{{ $rental->id }}">-</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada penyewaan aktif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="customerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Detail Customer</h3>
            <button type="button" id="closeCustomerModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <div class="space-y-2 text-sm">
            <p><span class="font-semibold">Nama:</span> <span id="mNama"></span></p>
            <p><span class="font-semibold">Kontak/WA:</span> <span id="mKontak"></span></p>
            <p><span class="font-semibold">Alamat:</span> <span id="mAlamat"></span></p>
            <p><span class="font-semibold">No. Identitas/KTP:</span> <span id="mKtp"></span></p>
            <div class="mt-3">
                <span class="font-semibold">Foto KTP:</span>
                <img id="mFoto" src="" alt="Foto KTP" class="mt-2 max-w-full rounded border hidden">
                <p id="mFotoEmpty" class="text-gray-500 text-sm mt-1">Tidak ada foto.</p>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('customerModal');
    document.querySelectorAll('.customer-modal-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('mNama').textContent = btn.dataset.nama;
            document.getElementById('mKontak').textContent = btn.dataset.kontak;
            document.getElementById('mAlamat').textContent = btn.dataset.alamat;
            document.getElementById('mKtp').textContent = btn.dataset.ktp;
            const fotoUrl = convertDriveUrl(btn.dataset.foto);
            const img = document.getElementById('mFoto');
            const empty = document.getElementById('mFotoEmpty');
            if (fotoUrl) {
                img.src = fotoUrl;
                img.classList.remove('hidden');
                empty.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                empty.classList.remove('hidden');
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    function convertDriveUrl(url) {
        if (!url) return '';
        let match = url.match(/\/d\/([a-zA-Z0-9_-]+)/) || url.match(/[?&]id=([a-zA-Z0-9_-]+)/);
        if (match) {
            return 'https://drive.google.com/uc?export=view&id=' + match[1];
        }
        if (url.includes('/view')) {
            return url.replace('/view', '&export=download');
        }
        return url;
    }
    document.getElementById('closeCustomerModal').addEventListener('click', function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    function pad(n) { return String(n).padStart(2, '0'); }

    function renderTimers() {
        const now = new Date().getTime();
        document.querySelectorAll('.timer-cell').forEach(function (cell) {
            const kembaliDate = new Date(cell.getAttribute('data-kembali'));
            const rentalId = cell.getAttribute('data-rental-id');
            if (isNaN(kembaliDate.getTime())) {
                cell.textContent = '-';
                return;
            }
            const distance = kembaliDate.getTime() - new Date().getTime();
            if (distance > 0) {
                const totalSeconds = Math.floor(distance / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                cell.textContent = pad(hours) + ':' + pad(minutes) + ':' + pad(seconds);
            } else if (distance > -3600000) {
                cell.innerHTML = '<a href="{{ url('/rental/scan-return') }}/' + rentalId + '" class="inline-block bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Return</a>';
            } else {
                cell.innerHTML = '<a href="{{ url('/rental/scan-return') }}/' + rentalId + '" class="inline-block bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Late Return</a>';
            }
        });
    }
    renderTimers();
    setInterval(renderTimers, 1000);
</script>
@endsection

