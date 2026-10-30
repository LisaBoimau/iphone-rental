@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Data Customer</h2>
        <button id="openCustomerModal" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">+ Tambah Customer</button>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nama</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">No WhatsApp</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Alamat</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">No KTP</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Foto KTP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($customers as $customer)
                    <tr>
                        <td class="px-4 py-2">{{ $customer->nama_lengkap }}</td>
                        <td class="px-4 py-2">{{ $customer->no_kontak }}</td>
                        <td class="px-4 py-2">{{ $customer->alamat }}</td>
                        <td class="px-4 py-2">{{ $customer->no_identitas }}</td>
                        <td class="px-4 py-2">
                            @if ($customer->foto_ktp)
                                <button class="view-ktp-btn bg-gray-700 text-white px-3 py-1 rounded text-sm hover:bg-gray-800" data-src="{{ $customer->foto_ktp }}">Lihat KTP</button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="customerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Tambah Customer</h3>
            <button type="button" id="closeCustomerModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ url('/customers') }}" class="space-y-3">
            @csrf
            <input name="nama_lengkap" placeholder="Nama Lengkap" class="w-full border rounded px-3 py-2" required>
            <input name="no_kontak" placeholder="No WhatsApp" class="w-full border rounded px-3 py-2" required>
            <textarea name="alamat" placeholder="Alamat" class="w-full border rounded px-3 py-2" required></textarea>
            <input name="no_identitas" placeholder="No KTP" class="w-full border rounded px-3 py-2" required>
            <input name="foto_ktp" placeholder="Foto KTP (path/url)" class="w-full border rounded px-3 py-2">
            <button class="w-full bg-blue-600 text-white rounded px-4 py-2 font-semibold hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</div>

<div id="ktpModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow-lg w-full max-w-2xl p-6 text-center">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Foto KTP</h3>
            <button type="button" id="closeKtpModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <img id="ktpImage" src="" alt="Foto KTP" class="max-w-full max-h-[70vh] mx-auto rounded border">
    </div>
</div>

<script>
    const ktpModal = document.getElementById('ktpModal');
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
    document.querySelectorAll('.view-ktp-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('ktpImage').src = convertDriveUrl(btn.dataset.src);
            ktpModal.classList.remove('hidden');
            ktpModal.classList.add('flex');
        });
    });
    document.getElementById('closeKtpModal').addEventListener('click', function () {
        ktpModal.classList.add('hidden');
        ktpModal.classList.remove('flex');
    });
</script>
<script>
    const cModal = document.getElementById('customerModal');
    document.getElementById('openCustomerModal').addEventListener('click', function () {
        cModal.classList.remove('hidden');
        cModal.classList.add('flex');
    });
    document.getElementById('closeCustomerModal').addEventListener('click', function () {
        cModal.classList.add('hidden');
        cModal.classList.remove('flex');
    });
</script>
@endsection

