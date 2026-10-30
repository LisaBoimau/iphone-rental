@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Scan Peminjaman</h2>

    <div class="flex justify-center gap-3 mb-4">
        <button id="useCameraBtn" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Gunakan Kamera</button>
        <button id="manualBtn" class="bg-gray-600 text-white px-4 py-2 rounded font-semibold hover:bg-gray-700">Input Manual</button>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

<div id="cameraSection">
    <div id="reader" class="w-full max-w-md mx-auto"></div>
    <p id="scanStatus" class="text-center mt-3 text-sm text-gray-600">Arahkan kamera ke QR iPhone...</p>

    <div id="borrowForm" class="hidden mt-6 max-w-2xl mx-auto">
        <form method="POST" action="{{ url('/rental/scan-borrow') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="iphone_id" id="iphone_id">

            <div class="p-3 bg-blue-50 rounded">
                <span class="font-semibold">Unit:</span> <span id="unitInfo"></span>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Pilih Customer (opsional)</label>
                <select name="customer_id" id="customer_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Isi data customer baru --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->nama_lengkap }} ({{ $customer->no_kontak }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="nama_lengkap" placeholder="Nama Lengkap (jika baru)" class="border rounded px-3 py-2">
                <input name="no_kontak" placeholder="No Kontak/WA (jika baru)" class="border rounded px-3 py-2">
                <input name="no_identitas" placeholder="No Identitas/KTP (jika baru)" class="border rounded px-3 py-2">
                <input name="foto_ktp" placeholder="Foto KTP (path/url)" class="border rounded px-3 py-2">
            </div>
            <textarea name="alamat" placeholder="Alamat (jika baru)" class="w-full border rounded px-3 py-2"></textarea>

            <div>
                <label class="block text-sm font-medium mb-1">Durasi Sewa (jam)</label>
                <input name="durasi_jam" type="number" min="1" value="24" class="w-full border rounded px-3 py-2" required>
            </div>

            <button class="bg-green-600 text-white rounded px-4 py-2 font-semibold hover:bg-green-700">Start</button>
        </form>
    </div>
</div>

    <div id="manualSection" class="hidden mt-6 max-w-2xl mx-auto">
        <form method="POST" action="{{ url('/rental/scan-borrow') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Pilih iPhone (Tersedia)</label>
                <select name="iphone_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Unit --</option>
                    @foreach ($iphones as $iphone)
                        <option value="{{ $iphone->id }}">{{ $iphone->tipe_iphone }} - {{ $iphone->imei }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Pilih Customer (opsional)</label>
                <select name="customer_id" class="w-full border rounded px-3 py-2">
                    <option value="">-- Isi data customer baru --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->nama_lengkap }} ({{ $customer->no_kontak }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="nama_lengkap" placeholder="Nama Lengkap (jika baru)" class="border rounded px-3 py-2">
                <input name="no_kontak" placeholder="No Kontak/WA (jika baru)" class="border rounded px-3 py-2">
                <input name="no_identitas" placeholder="No Identitas/KTP (jika baru)" class="border rounded px-3 py-2">
                <input name="foto_ktp" placeholder="Foto KTP (path/url)" class="border rounded px-3 py-2">
            </div>
            <textarea name="alamat" placeholder="Alamat (jika baru)" class="w-full border rounded px-3 py-2"></textarea>

            <div>
                <label class="block text-sm font-medium mb-1">Durasi Sewa (jam)</label>
                <input name="durasi_jam" type="number" min="1" value="24" class="w-full border rounded px-3 py-2" required>
            </div>

            <button class="bg-green-600 text-white rounded px-4 py-2 font-semibold hover:bg-green-700">Start Peminjaman</button>
        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    const readerEl = document.getElementById('reader');
    const statusEl = document.getElementById('scanStatus');
    const html5QrCode = new Html5Qrcode('reader');
    let scanning = true;

    function onScanSuccess(decodedText) {
        if (!scanning) return;
        scanning = false;
        statusEl.textContent = 'Memvalidasi unit...';

        fetch('{{ url('/rental/lookup-iphone') }}?code=' + encodeURIComponent(decodedText))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (!data.success) {
                    statusEl.textContent = data.message || 'Unit tidak ditemukan.';
                    scanning = true;
                    return;
                }
                if (data.iphone.status !== 'Tersedia') {
                    statusEl.textContent = 'Unit tidak tersedia (status: ' + data.iphone.status + ').';
                    scanning = true;
                    return;
                }
                document.getElementById('iphone_id').value = data.iphone.id;
                document.getElementById('unitInfo').textContent = data.iphone.tipe_iphone + ' (IMEI: ' + data.iphone.imei + ')';
                document.getElementById('borrowForm').classList.remove('hidden');
                statusEl.textContent = 'Unit tersedia. Lengkapi data customer dan durasi.';
                html5QrCode.stop();
            })
            .catch(function () {
                statusEl.textContent = 'Terjadi kesalahan validasi.';
                scanning = true;
            });
    }

    function startCamera() {
        document.getElementById('cameraSection').classList.remove('hidden');
        document.getElementById('manualSection').classList.add('hidden');
        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            function () {}
        ).catch(function (err) {
            statusEl.textContent = 'Gagal mengakses kamera: ' + err;
        });
    }

    function stopCamera() {
        try { html5QrCode.stop(); } catch (e) {}
    }

    document.getElementById('useCameraBtn').addEventListener('click', function () {
        startCamera();
    });

    document.getElementById('manualBtn').addEventListener('click', function () {
        stopCamera();
        document.getElementById('cameraSection').classList.add('hidden');
        document.getElementById('manualSection').classList.remove('hidden');
    });

    startCamera();
</script>
@endsection
