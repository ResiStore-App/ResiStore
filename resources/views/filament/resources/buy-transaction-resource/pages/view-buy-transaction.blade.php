<x-filament::page>
    <div class="space-y-6">
        <div class="text-2xl font-bold text-gray-800">Detail Transaksi</div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="p-4 rounded-lg bg-white shadow border">
                <div class="text-gray-500">Tanggal Transaksi</div>
                <div class="font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($record->tanggal_transaksi)->translatedFormat('d F Y') }}
                </div>
            </div>
            <div class="p-4 rounded-lg bg-white shadow border">
                <div class="text-gray-500">Kasir / Petugas</div>
                <div class="font-medium text-gray-900">{{ $record->user->name }}</div>
            </div>
            <div class="p-4 rounded-lg bg-white shadow border">
                <div class="text-gray-500">Total Harga</div>
                <div class="font-semibold text-green-700 text-lg">
                    Rp {{ number_format($record->total_harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6 text-center">Detail Barang</h3>

           <div class="mx-auto w-full overflow-x-auto rounded-xl shadow border border-gray-200 bg-white">
    <table class="w-full table-auto divide-y divide-gray-200 text-sm text-gray-800">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left font-semibold text-gray-600 w-1/3">Nama Barang</th>
                <th class="px-6 py-3 text-center font-semibold text-gray-600 w-1/6">Jumlah</th>
                <th class="px-6 py-3 text-right font-semibold text-gray-600 w-1/4">Harga Satuan</th>
                <th class="px-6 py-3 text-right font-semibold text-gray-600 w-1/4">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($details as $detail)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $detail->barang->nama_barang ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">{{ $detail->kuantitas }}</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right font-medium">Rp {{ number_format($detail->kuantitas * $detail->harga_satuan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-6 text-center text-gray-500">Tidak ada detail barang</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

        </div>
    </div>
</x-filament::page>
