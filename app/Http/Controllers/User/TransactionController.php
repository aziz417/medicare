<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())->latest()->paginate(15);
        return view('user.payments.list', compact('transactions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('access-transaction', $transaction);
        return view('user.payments.view', compact('transaction'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('access-transaction', $transaction);
        if( $transaction->isPending() && $transaction->delete() ){
            return back()->withSuccess("Transaction deleted successfully!");
        }
        return back()->withWarning("Failed to delete Transaction!");
    }
}
