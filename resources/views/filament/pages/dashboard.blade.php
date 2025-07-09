<x-filament::page>
    <div class="mb-6">
        <div class="rounded-xl text-black">
            <div class="flex items-center justify-between">
                <div class>
                <h1 class="text-2xl font-bold text-black">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                    
                </div>
                <div class="md:block rounded-lg p-3 text-center flex flex-row">
                    <p class="text-black mt-1">{{ now()->format('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[calc(100vh-200px)]">
        <div class="space-y-4">

            <div class="flex flex-row gap-2 w-full">
                <div class="flex flex-col w-1/2 gap-3">
                    <div class="bg-white rounded-xl shadow-sm border p-4 hover:shadow-md transition-shadow h-full">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-600">Transaksi Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Transaction::whereDate('tanggal_transaksi', today())->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border p-4 hover:shadow-md transition-shadow h-full">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-600">Pendapatan Hari Ini</p>
                                @php
                                    $pendapatan = \App\Models\Transaction::whereDate('tanggal_transaksi', today())
                                        ->where('jenis_transaksi', 'penjualan')
                                        ->sum('total_harga');
                                @endphp
                                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-2 bg-yellow-100 rounded-lg">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full">
                    <div class="bg-white rounded-xl shadow-sm border h-full overflow-hidden w-full">
                        <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                            <h3 class="text-lg font-semibold text-green-700 flex items-center">
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
                                            <div class="flex flex-row p-3 bg-green-50 rounded-lg">
                                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                    <span class="font-medium text-green-600">{{ $index + 1 }}</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-gray-800 text-sm truncate">{{ $item->barang->nama_barang }}</p>
                                                    <p class="text-xs text-gray-600">{{ $item->total }} terjual</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-16">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium text-sm">Belum ada data</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border h-full overflow-hidden">
                <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                    <h3 class="text-lg font-semibold text-red-700 flex items-center">
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
                                <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-black text-sm">{{ $product->nama_barang }}</p>
                                            <p class="text-xs text-gray-600 mt-1">Kode Barang: {{ $product-> id ?? 'N/A' }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            {{ $product->stok }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Semua stok aman!</p>
                            <p class="text-sm text-gray-400">Tidak ada barang dengan stok kurang dari 10</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        
    </div>
</x-filament::page>