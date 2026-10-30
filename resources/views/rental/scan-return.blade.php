@extends('layouts.app')

@section('content')
<div class="bg-white rounded shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Scan Pengembalian</h2>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="flex justify-center gap-3 mb-4">
        <button id="useCameraBtn2" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Gunakan Kamera</button>
        <button id="manualBtn2" class="bg-gray-600 text-white px-4 py-2 rounded font-semibold hover:bg-gray-700">Validasi Manual</button>
    </div>

    <div class="mb-4 p-3 bg-gray-50 rounded text-sm">
        <p><span class="font-semibold">Customer:</span> {{ $rental->customer->nama_lengkap ?? '-' }}</p>
        <p><span class="font-semibold">iPhone:</span> {{ $rental->iphone->tipe_iphone ?? '-' }} (IMEI: {{ $rental->iphone->imei ?? '-' }})</p>
        <p><span class="font-semibold">Tgl Kembali:</span> {{ $rental->tgl_kembali }}</p>
    </div>

    <div id="cameraSection2">
    <div id="reader" class="w-full max-w-md mx-auto"></div>
    <p id="scanStatus" class="text-center mt-3 text-sm text-gray-600">Arahkan kamera ke QR iPhone untuk validasi unit...</p>

    <div id="resultSection" class="hidden mt-6 max-w-md mx-auto space-y-4">
        <div id="lateCard" class="hidden p-4 border-2 border-red-500 bg-red-50 rounded">
            <h3 class="font-semibold text-red-700 mb-2">Tagihan Keterlambatan</h3>
            <p>Keterlambatan: <span id="lateDays"></span> hari</p>
            <p>Denda: Rp <span id="lateFine"></span></p>
            <p class="text-xs text-gray-600 mt-1">Tarif Rp 50.000 / hari keterlambatan.</p>
        </div>
        <div id="ontimeCard" class="hidden p-4 border-2 border-green-500 bg-green-50 rounded">
            <h3 class="font-semibold text-green-700 mb-2">Tepat Waktu</h3>
            <p>Unit siap diterima tanpa denda.</p>
        </div>

        <form method="POST" action="{{ url('/rental/scan-return/' . $rental->id) }}">
            @csrf
            <button class="w-full bg-orange-500 text-white rounded px-4 py-2 font-semibold hover:bg-orange-600">Selesaikan Peminjaman</button>
        </form>
    </div>

    <div id="manualSection2" class="hidden mt-6 max-w-md mx-auto">
        <form method="POST" action="{{ url('/rental/scan-return/' . $rental->id) }}" class="space-y-3">
            @csrf
            <div class="p-3 bg-gray-50 rounded text-sm">
                <p><span class="font-semibold">Tipe iPhone:</span> {{ $rental->iphone->tipe_iphone ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">IMEI/QR</label>
                <input name="manual_code" placeholder="Masukkan IMEI atau QR Code" class="w-full border rounded px-3 py-2" required>
            </div>
            @error('manual_code')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <button class="w-full bg-orange-500 text-white rounded px-4 py-2 font-semibold hover:bg-orange-600">Konfirmasi Pengembalian</button>
        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    const statusEl = document.getElementById('scanStatus');
    const expectedCodes = ['{{ $rental->iphone->qr_code_string }}', '{{ $rental->iphone->imei }}'];
    const kembaliStr = '{{ $rental->tgl_kembali->toISOString() }}';
    const html5QrCode = new Html5Qrcode('reader');
    let scanning = true;

    function showResult() {
        const now = new Date();
        const kembali = new Date(kembaliStr);
        const diff = now - kembali;
        document.getElementById('resultSection').classList.remove('hidden');

        if (diff > 0) {
            const days = Math.max(1, Math.ceil(diff / (1000 * 60 * 60 * 24)));
            document.getElementById('lateDays').textContent = days;
            document.getElementById('lateFine').textContent = (days * 50000).toLocaleString('id-ID');
            document.getElementById('lateCard').classList.remove('hidden');
        } else {
            document.getElementById('ontimeCard').classList.remove('hidden');
        }
    }

    function onScanSuccess(decodedText) {
        if (!scanning) return;
        if (!expectedCodes.includes(decodedText)) {
            statusEl.textContent = 'QR tidak cocok dengan unit ini. Coba lagi.';
            return;
        }
        scanning = false;
        statusEl.textContent = 'Validasi berhasil.';
        html5QrCode.stop();
        showResult();
    }

    function startCamera2() {
        document.getElementById('cameraSection2').classList.remove('hidden');
        document.getElementById('manualSection2').classList.add('hidden');
        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            function () {}
        ).catch(function (err) {
            statusEl.textContent = 'Gagal mengakses kamera: ' + err;
        });
    }

    function stopCamera2() {
        try { html5QrCode.stop(); } catch (e) {}
    }

    document.getElementById('useCameraBtn2').addEventListener('click', function () {
        startCamera2();
    });

    document.getElementById('manualBtn2').addEventListener('click', function () {
        stopCamera2();
        document.getElementById('cameraSection2').classList.add('hidden');
        document.getElementById('manualSection2').classList.remove('hidden');
    });

    startCamera2();
</script>
@endsection
