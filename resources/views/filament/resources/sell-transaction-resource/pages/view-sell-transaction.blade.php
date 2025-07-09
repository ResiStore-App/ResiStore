<x-filament::page>
    <div class="space-y-6">
        <div class="text-2xl font-bold text-gray-800 dark:text-white">Detail Transaksi Penjualan</div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="p-4 bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="text-gray-500 dark:text-gray-400">Tanggal Transaksi</div>
                <div class="font-medium text-gray-900 dark:text-white">{{ $record->tanggal_transaksi }}</div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="text-gray-500 dark:text-gray-400">Kasir</div>
                <div class="font-medium text-gray-900 dark:text-white">{{ $record->user->name }}</div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="text-gray-500 dark:text-gray-400">Total</div>
                <div class="font-semibold text-green-600 dark:text-green-400 text-lg">
                    Rp {{ number_format($record->total_harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-center text-gray-800 dark:text-white mt-8 mb-4">Detail Barang</h3>
            <div class="flex justify-center">
                <div class="mx-auto w-full overflow-x-auto rounded-xl shadow border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <table class="w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nama Barang</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Jumlah</th>
                                <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Harga</th>
                                <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($record->details as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
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
