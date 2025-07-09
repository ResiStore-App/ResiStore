<x-filament::page>
    <div class="space-y-6">
        <div class="text-2xl font-bold text-gray-800">Detail Transaksi Penjualan</div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="p-4 bg-white shadow border rounded-lg">
                <div class="text-gray-500">Tanggal Transaksi</div>
                <div class="font-medium">{{ $record->tanggal_transaksi }}</div>
            </div>
            <div class="p-4 bg-white shadow border rounded-lg">
                <div class="text-gray-500">Kasir</div>
                <div class="font-medium">{{ $record->user->name }}</div>
            </div>
            <div class="p-4 bg-white shadow border rounded-lg">
                <div class="text-gray-500">Total</div>
                <div class="font-semibold text-green-600 text-lg">Rp {{ number_format($record->total_harga, 0, ',', '.') }}</div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-center text-gray-800 mt-8 mb-4">Detail Barang</h3>
            <div class="flex justify-center">
                <div class="mx-auto w-full overflow-x-auto rounded-xl shadow border border-gray-200 bg-white">
                    <table class="w-full table-auto divide-y divide-gray-200 text-sm text-gray-800">
                         <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Nama Barang</th>
                                <th class="px-6 py-3 text-center font-semibold">Jumlah</th>
                                <th class="px-6 py-3 text-right font-semibold">Harga</th>
                                <th class="px-6 py-3 text-right font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($record->details as $item)
                                <tr>
                                    <td class="px-6 py-4">{{ $item->barang->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">{{ $item->kuantitas }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-medium">Rp {{ number_format($item->kuantitas * $item->harga_satuan, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
