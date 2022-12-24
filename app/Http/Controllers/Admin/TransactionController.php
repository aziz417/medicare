<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Appointment\Confirmed;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:master|admin|doctor')->except('destroy');
        $this->middleware('role:master|admin|doctor')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = Transaction::whereNotIn('type', ['withdraw'])->latest()->with('user')->paginate(20);
        return view('admin.transactions.list', compact('transactions'));
    }

    public function doctorTransactions(Request $request)
    {
        $transactions = Transaction::whereIn('type', ['withdraw'])->latest()->with('user')->paginate(20);
        return view('admin.transactions.doctorList', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('admin.transactions.view', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update-transaction', $transaction);
        $request->validate([
            'action' => 'required|in:approved,rejected',
            'received_amount' => 'required',
            'comment' => 'nullable|string'
        ]);
        if( $request->action == 'approved' ){
            $transaction->action('approved', [
                'admin-comment' => $request->comment ?? 'Transaction Approved!',
            ]);
            $transaction->update([
                'received_amount' => $request->received_amount ?? $transaction->final_amount
            ]);
            $transaction->appointments()->each(function($item)use($transaction, $request){
                try {
                    $item->doctor->getWallet()->add($transaction->received_amount);
                    $item->update([
                        'status' => 'approved'
                    ]);
                    event(new Confirmed($item));
                    $name = $request->user()->name;
                    $notifier = $item->patient->isSubmember() ? $item->user : $item->patient;
                    sendNotification($notifier, "Your Appointment {$item->appointment_code} confirmed by {$name}", [
                        'icon' => 'check', 'link' => route('user.appointments.show', $item->id)
                    ]);
                } catch (Exception $e) {info("Failed to add balance to doctor wallet!");}
            });
        }elseif( $request->action == 'rejected' ){
            $transaction->action('rejected', [
                'reason' => $request->comment ?? 'Transaction Rejected!',
            ]);
        }
        return back()->withInfo("Transaction {$request->action} successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete-transaction', $transaction);
        if( $transaction->isConfirmed() ){
            return back()->withInfo("Approved transaction could not deletable!");
        }
        if( $transaction->isPending() && $transaction->type == 'withdraw' ){
            $transaction->user->getWallet()->cancelWithdraw($transaction->final_amount);
        }
        if( $transaction->delete() ){
            return back()->withSuccess("Transaction deleted successfully!");
        }
        return back()->withWarning("Failed to delete Transaction!");
    }
}
