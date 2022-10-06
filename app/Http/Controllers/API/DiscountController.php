<?php

namespace App\Http\Controllers\API;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    public function checkCouponCode(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'user_id' => 'nullable'
        ]);

        $discount = Discount::checkCode($request->coupon_code, $request->user_id);
        if( $discount === false ){
            return response()->json([
                'status' => false,
                'message' => "Invalid or Expired Coupon Code!"
            ]);
        }
        $discount_data = Discount::where('code', $request->coupon_code)->first();
        if ($discount_data->is_percentage && ($discount_data->is_percentage == 1)){
            $message = "Valid Coupon Code, You get ".$discount."% discount!";
            $amount = $value = ($discount / 100) * $request->charge;
        }else{
            $message = "Valid Coupon Code, You get ".inCurrency($discount)." discount!";
            $amount = $discount;
        }
        return response()->json([
            'status' => true,
            'message' => $message,
            'discount' => $amount
        ]);
    }
}
