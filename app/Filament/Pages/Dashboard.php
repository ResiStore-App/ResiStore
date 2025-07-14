<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Gemini\Laravel\Facades\Gemini;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    public string $prompt = 'Rekomendasi';
    public string $jawaban = '';

    private function cleanMarkdown(string $text): string
    {
        return preg_replace('/(\*\*|\*)/', '', $text);
    }
    public function generateFromGemini(): void
    {
        $topItems = \App\Models\DetailTransaction::select('id_barang')
        ->join('tb_transaksi', 'tb_detail_transaksi.id_transaksi', '=', 'tb_transaksi.id')
        ->where('tb_transaksi.jenis_transaksi', 'penjualan')
        ->selectRaw('SUM(tb_detail_transaksi.kuantitas) as total')
        ->groupBy('id_barang')
        ->orderByDesc('total')
        ->with('barang')
        ->limit(3)
        ->get();

        $topText = $topItems
            ->filter(fn($item) => $item->barang)
            ->map(fn($item, $i) => ($i+1) . ". {$item->barang->nama_barang} ({$item->total}x)")
            ->implode(", ");

        $nonSoldItems = \App\Models\Product::whereDoesntHave('detailTransaksi.transaksi', function ($query) {
            $query->where('jenis_transaksi', 'penjualan');
        })->get();

        $nonSoldText = $nonSoldItems->pluck('nama_barang')->implode(', ');
        $prompt = <<<PROMPT
        Berikut ini adalah produk terlaris: $topText.
        Dan produk yang belum pernah terjual: $nonSoldText.
        Apa insight yang bisa diberikan untuk meningkatkan penjualan produk yang tidak laku?
        Format permintaan sebagai berikut:
        - Tulis jawaban dalam bentuk list bernomor (1. 2. 3. dst).
        - Setiap poin HARUS berada di baris/paragraf terpisah.
        - Jangan gabungkan beberapa poin dalam satu paragraf panjang.
        - Jangan gunakan markdown seperti **bold**, *italic*, atau tanda kutip.
        - Jangan awali dengan kata "Jawaban:" atau penjelasan pembuka.

        Contoh format yang diinginkan:

        1. Pertama, evaluasi mengapa produk tidak laku. Mungkin karena harga tidak kompetitif atau kurang promosi.

        2. Selanjutnya, lakukan promosi seperti diskon, bundling, atau penempatan strategis di toko.

        3. Pertimbangkan untuk menghapus produk yang tidak relevan seperti produk dengan nama aneh atau tidak informatif.
        PROMPT;
            
        $result = Gemini::generativeModel('gemini-2.0-flash')->generateContent($prompt);
        $text = $result->text();
        $this->jawaban = $this->cleanMarkdown($text);
        }
        protected function getViewData(): array
        {
            return [
                'jawaban' => $this->jawaban,
            ];
        }
}
