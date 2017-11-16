<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Etsy\Models\Transaction;

class TransactionsController extends Controller
{

    public function index(Request $request) {
      $page = isset($request->page) ? $request->page : 1;
      $transaction = new Transaction();
      $transactions = $transaction->fetchAllTransactions($page);
      dd($transactions);
      //return view("receipts.index", ["collection" => $receipts]);
    }
}
