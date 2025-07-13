<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Penjualan - Resi Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Optional: Print specific styles */
        @media print {
            body {
                background-color: #fff;
            }
            .max-w-xl {
                max-width: none;
                width: 100%;
                box-shadow: none;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 to-purple-50 py-10 px-4">
    <div class="max-w-xl mx-auto bg-white shadow-xl rounded-lg p-8 border border-gray-200">
        <div class="text-center border-b-2 border-gray-200 pb-6 mb-6">
            <h1 class="text-3xl font-extrabold text-indigo-700 mb-2">Resi Store 🛍️</h1>
            <p class="text-md text-gray-700">Invoice Penjualan</p>
            <div class="mt-4 text-gray-600 text-sm">
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y H:i') }}</p>
                <p><strong>Kasir:</strong> {{ $transaksi->user->name }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Detail Barang:</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-indigo-50 text-indigo-700">
                        <tr>
                            <th scope="col" class="px-5 py-3">Nama Barang</th>
                            <th scope="col" class="px-5 py-3 text-center">Qty</th>
                            <th scope="col" class="px-5 py-3 text-right">Harga Satuan</th>
                            <th scope="col" class="px-5 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->details as $detail)
                            <tr class="border-b last:border-b-0 hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $detail->barang->nama_barang }}</td>
                                <td class="px-5 py-3 text-center">{{ $detail->kuantitas }}</td>
                                <td class="px-5 py-3 text-right">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right">Rp{{ number_format($detail->harga_satuan * $detail->kuantitas, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-t-2 border-gray-200 pt-6 mt-6">
            <div class="flex justify-between items-center text-gray-800 mb-3">
                <h4 class="text-xl font-bold">Total Harga</h4>
                <p class="text-xl font-extrabold text-indigo-700">
                    Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="text-center mt-8 text-sm text-gray-500 italic border-t border-dashed pt-4">
            <p>Terima kasih atas pembelian Anda! 🙏 Kami menantikan kunjungan Anda berikutnya.</p>
        </div>
    </div>
</body>
</html>