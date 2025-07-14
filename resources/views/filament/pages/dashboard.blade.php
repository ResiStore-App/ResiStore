<x-filament::page>
    <div class="mb-6">
        <div class="rounded-xl text-gray-900 dark:text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Selamat Datang, {{ auth()->user()->name }}! 👋
                    </h1>
                </div>
                <div class="md:block rounded-lg p-3 text-center flex flex-row">
                    <p class="mt-1 text-gray-600 dark:text-gray-100">
                        {{ now()->format('d F Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-200px)]">
        <div class="space-y-4">
            <div class="flex flex-row gap-2 w-full">
                <div class="flex flex-col w-1/2 gap-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow h-full">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Transaksi Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Transaction::whereDate('tanggal_transaksi', today())->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow h-full">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Pendapatan Hari Ini</p>
                                @php
                                    $pendapatan = \App\Models\Transaction::whereDate('tanggal_transaksi', today())
                                        ->where('jenis_transaksi', 'penjualan')
                                        ->sum('total_harga');
                                @endphp
                                <p class="text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-200 rounded-lg"></div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full overflow-hidden w-full">
                        <div class="bg-green-50 dark:bg-green-900 px-6 py-4 border-b border-green-100 dark:border-green-800">
                            <h3 class="text-lg font-semibold text-green-700 dark:text-green-300 flex items-center">
                                Top Produk
                            </h3>
                        </div>
                        <div class="p-4 overflow-hidden" style="height: calc(100% - 80px);">
                            @php
                                $topItems = \App\Models\DetailTransaction::select('id_barang')
                                    ->join('tb_transaksi', 'tb_detail_transaksi.id_transaksi', '=', 'tb_transaksi.id')
                                    ->where('tb_transaksi.jenis_transaksi', 'penjualan')
                                    ->selectRaw('SUM(tb_detail_transaksi.kuantitas) as total')
                                    ->groupBy('id_barang')
                                    ->orderByDesc('total')
                                    ->with('barang')
                                    ->limit(3)
                                    ->get();
                            @endphp

                            @if ($topItems->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($topItems as $index => $item)
                                        @if ($item->barang)
                                            <div class="flex flex-row p-3 bg-green-50 dark:bg-green-800 rounded-lg">
                                                <div class="w-6 h-6 bg-green-100 dark:bg-green-700 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                    <span class="font-medium text-green-600 dark:text-green-200">{{ $index + 1 }}</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-gray-800 dark:text-white text-sm truncate">{{ $item->barang->nama_barang }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $item->total }} terjual</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-16">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">Belum ada data</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full overflow-hidden">
                <div class="bg-red-50 dark:bg-red-900 px-6 py-4 border-b border-red-100 dark:border-red-700">
                    <h3 class="text-lg font-semibold text-red-700 dark:text-red-300 flex items-center">
                        Stok Barang Menipis
                    </h3>
                </div>
                <div class="p-6 overflow-y-auto" style="height: calc(100% - 80px);">
                    @php
                        $stokMenipis = \App\Models\Product::where('stok', '<', 10)->get();
                    @endphp
                    
                    @if($stokMenipis->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($stokMenipis as $product)
                                <div class="p-4 bg-red-50 dark:bg-red-800 rounded-lg border border-red-100 dark:border-red-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $product->nama_barang }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Kode Barang: {{ $product->id ?? 'N/A' }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-200 ml-2">
                                            {{ $product->stok }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Semua stok aman!</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Tidak ada barang dengan stok kurang dari 10</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="flex gap-3">
            <input 
                disabled
                wire:model="prompt"
                type="text"
                placeholder="gemini"
                class="w-full border-gray-300 rounded-lg dark:bg-gray-800 dark:text-white"
            />

            <x-filament::button wire:click="generateFromGemini">
                Tanya Gemini
            </x-filament::button>
        </div>

        @if ($jawaban)
            <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg mt-4 whitespace-pre-line">
                <h3 class="font-semibold mb-2">Jawaban:</h3>
                <p class="text-gray-900 dark:text-white">{{ $jawaban }}</p>
            </div>
        @endif
    </div>
</x-filament::page>
