<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::latest()->paginate(20);
        $users = User::whereRole(['doctor', 'patient', 'user'])->get(['id', 'name', 'role']);
        return view('admin.discounts.list', compact('discounts', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|min:3|unique:discounts',
            'amount' => "required",
            'is_percentage' => "required",
            'expire_at' => "nullable|date",
            'limit' => "required|numeric",
            'available_for' => "nullable|numeric",
        ]);
        $discount = Discount::create($validated);
        if( $discount ){
            return back()->withSuccess("Discount added successfully!");
        }
        return back()->withWarning("Failed to add Discount!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'expire_at' => "nullable|date",
            'limit' => "required|numeric",
            'available_for' => "nullable|numeric",
        ]);
        $discount->fill($validated);
        if( $discount->save() ){
            return back()->withSuccess("Discount updated successfully!");
        }
        return back()->withWarning("Failed to update Discount!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        if( $discount->delete() ){
            return back()->withSuccess("Discount deleted successfully!");
        }
        return back()->withWarning("Failed to delete Discount!");
    }
}
