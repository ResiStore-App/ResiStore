<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class StrukController extends Controller
{
    public function print($id) {
        $transaksi = Transaction::with(['user', 'details.barang'])->findOrFail($id);
        return view('struk.print', compact('transaksi'));
    }
}
