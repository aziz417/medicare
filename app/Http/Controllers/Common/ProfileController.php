<?php

namespace App\Http\Controllers\Common;

use App\Models\Department;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdate;

class ProfileController extends Controller
{
    public function index()
    {
        return view('common.profile.view');
    }

    public function edit()
    {
        $departments = Department::all();
        return view('common.profile.edit', compact('departments'));
    }

    public function wallet()
    {
        $wallet = auth()->user()->getWallet();
        $withdrawals = Transaction::where([
            'user_id' => auth()->id(),'type' => 'withdraw'
        ])->latest()->limit(10)->get();
        return view('common.profile.wallet', compact('wallet', 'withdrawals'));
    }
    public function withdraw(Request $request)
    {
        $user = $request->user();
        $wallet = $user->getWallet();
        $request->validate([
            'amount' => 'required|integer|min:100|max:10000',
            'method' => 'required',
            'account_number' => 'required_if:method,mobile|max:12|min:10',
            'bank_account_name' => 'required_if:method,bank',
            'bank_account_number' => 'required_if:method,bank',
            'bank_name' => 'required_if:method,bank',
            'bank_branch_name' => 'required_if:method,bank',
            'bank_routing_number' => 'nullable',
        ]);
        if( !($wallet->amount >= $request->amount)){
            return back()->withInput()->withWarning("Insufficient Balance!");
        }
        $bank = $request->only([
            'bank_account_name', 'bank_account_number', 'bank_name',
            'bank_branch_name', 'bank_routing_number',
        ]);
        $mobile = strlen($request->account_number) == 12 ? ['Rocket'=>$request->account_number] : ['Bkash'=>$request->account_number];
        $response = [
            'message' => $request->message ?? "User Data",
            'data' => $request->method == 'bank' ? $bank : $mobile
        ];
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'gateway' => $request->method,
            'amount' => $request->amount,
            'discount' => 0,
            'final_amount' => $request->amount,
            'received_amount' => $request->amount,
            'tax' => 0,
            'response' => $response,
            'description' => "Balance Withdrawals",
            'type' => 'withdraw',
        ]);

        if( $transaction ){
            $wallet->withdraw($transaction->final_amount);
            return back()->withSuccess('Withdrawal request submitted, you will be notified when approved/reject!');
        }
        return back()->withWarning('Something is wrong!');
    }

    public function update(ProfileUpdate $request)
    {
        $user = $request->user();
        $userData = [];
        foreach ($request->all() as $key => $value) {
            if( str()->startsWith($key, 'user_') ){
                $userData[substr($key, strlen('user_'))] = $value;
            }
            if( str()->startsWith($key, 'charge_') ){
                $user->charges()->updateOrCreate(
                    ['type' => substr($key, strlen('charge_'))],
                    ['amount' => $value]
                );
            }
            if( str()->startsWith($key, 'meta_') ){
                $saveKey = "user_".substr($key, strlen('meta_'));
                $user->setMeta($saveKey, $value);
            }
        }
        $user->fill($userData)->save();

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $user->fill([ 'picture' => $image ])->save();
        }
        if( $request->hasFile('signature') && $signature = save_file($request->signature, 'uploads/user') ){
            $user->setMeta('user_signature', $signature);
        }

        return back()->withSuccess('Profile Updated Successfully!');
    }

    public function passwordUpdate(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'old_password' => "required|password_check:{$user->password}",
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user->fill(['password' => bcrypt($request->password)])->save();

        return back()->withSuccess('Password Updated Successfully!');
    }
}
