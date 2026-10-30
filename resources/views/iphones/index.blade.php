@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Data iPhone</h2>
        <button id="openIphoneModal" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">+ Tambah Device</button>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Tipe</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">IMEI</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">QR Code</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($iphones as $iphone)
                    <tr>
                        <td class="px-4 py-2">{{ $iphone->tipe_iphone }}</td>
                        <td class="px-4 py-2">{{ $iphone->imei }}</td>
                        <td class="px-4 py-2 text-xs">{{ $iphone->qr_code_string }}</td>
                        <td class="px-4 py-2">{{ $iphone->status }}</td>
                        <td class="px-4 py-2">
                            <button class="edit-iphone-btn bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 mr-1"
                                data-id="{{ $iphone->id }}"
                                data-tipe="{{ $iphone->tipe_iphone }}"
                                data-imei="{{ $iphone->imei }}">Edit</button>
                            <form method="POST" action="{{ url('/iphones/' . $iphone->id) }}" class="inline" onsubmit="return confirm('Hapus device ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Hapus</button>
                            </form>
                            <button class="print-qr-btn bg-gray-700 text-white px-3 py-1 rounded text-sm hover:bg-gray-800 ml-1" data-qr="{{ $iphone->qr_code_string }}">Print QR</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="iphoneModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Tambah Device</h3>
            <button type="button" id="closeIphoneModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ url('/iphones') }}" class="space-y-3">
            @csrf
            <input name="tipe_iphone" placeholder="Tipe iPhone" class="w-full border rounded px-3 py-2" required>
            <input name="imei" placeholder="IMEI" class="w-full border rounded px-3 py-2" required>
            <button class="w-full bg-blue-600 text-white rounded px-4 py-2 font-semibold hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</div>

<div id="editIphoneModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Edit Device</h3>
            <button type="button" id="closeEditIphoneModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form id="editIphoneForm" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <input name="tipe_iphone" id="edit_tipe" placeholder="Tipe iPhone" class="w-full border rounded px-3 py-2" required>
            <input name="imei" id="edit_imei" placeholder="IMEI" class="w-full border rounded px-3 py-2" required>
            <button class="w-full bg-blue-600 text-white rounded px-4 py-2 font-semibold hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</div>

<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded shadow-lg w-full max-w-sm p-6 text-center">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">QR Code</h3>
            <button type="button" id="closeQrModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <div id="qrContainer" class="flex justify-center mb-3"></div>
        <p id="qrText" class="text-xs text-gray-600 break-all mb-3"></p>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Cetak</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const iPhoneModal = document.getElementById('iphoneModal');
    document.getElementById('openIphoneModal').addEventListener('click', function () {
        iPhoneModal.classList.remove('hidden');
        iPhoneModal.classList.add('flex');
    });
    document.getElementById('closeIphoneModal').addEventListener('click', function () {
        iPhoneModal.classList.add('hidden');
        iPhoneModal.classList.remove('flex');
    });

    const editModal = document.getElementById('editIphoneModal');
    const editForm = document.getElementById('editIphoneForm');
    document.querySelectorAll('.edit-iphone-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('edit_tipe').value = btn.dataset.tipe;
            document.getElementById('edit_imei').value = btn.dataset.imei;
            editForm.action = '{{ url('/iphones') }}/' + btn.dataset.id;
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        });
    });
    document.getElementById('closeEditIphoneModal').addEventListener('click', function () {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    });

    const qrModal = document.getElementById('qrModal');
    document.querySelectorAll('.print-qr-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const code = btn.dataset.qr;
            const container = document.getElementById('qrContainer');
            container.innerHTML = '';
            new QRCode(container, { text: code, width: 200, height: 200 });
            document.getElementById('qrText').textContent = code;
            qrModal.classList.remove('hidden');
            qrModal.classList.add('flex');
        });
    });
    document.getElementById('closeQrModal').addEventListener('click', function () {
        qrModal.classList.add('hidden');
        qrModal.classList.remove('flex');
    });
</script>
@endsection

