<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen Peminjaman iPhone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md hidden md:block">
            <div class="p-4 font-bold text-lg border-b">iPhone Rental</div>
            <nav class="p-4 space-y-2">
                <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Dashboard</a>
                <a href="{{ url('/iphones') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Data iPhone</a>
                <a href="{{ url('/customers') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Data Customer</a>
                <a href="{{ url('/riwayat') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Riwayat Transaksi</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow p-4">
                <h1 class="text-xl font-semibold">Sistem Informasi Manajemen Peminjaman iPhone</h1>
            </header>

            <main class="flex-1 p-6">
                @yield('content')
            </main>

            <footer class="bg-white shadow p-4 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} iPhone Rental
            </footer>
        </div>
    </div>
</body>
</html>
