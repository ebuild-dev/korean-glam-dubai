<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::Orderby('id','Desc')->get();
        return view('admin.coupons.index')
            ->with('coupons', $coupons);
    }


    public function destroy(Request $request)
    {
        $coupon = Coupon::find($request->id_coupon);
        if ($coupon) {
            $coupon->delete();
            return redirect()
                ->back()
                ->with('success', 'Coupon supprimé avec succès');
        }
        return redirect()
            ->route('coupons.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|string',
            'valid_until' => 'required|date|after:Now',
            'pourcentage' => 'required|numeric'
        ]);
        $user = Auth::user();
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->valid_until = $request->valid_until;
        $coupon->by = $user->id;
        $coupon->pourcentage = $request->pourcentage;
        $coupon->save();
        return redirect()
            ->route('coupons.index')
            ->with('success', 'Coupon créé avec succès');
    }


    public function update(Request $request)
    {
        $coupon = Coupon::find($request->id);
        if ($coupon) {
            $request->validate([
                'code' => 'required|string',
                'valid_until' => 'required|date|after:Now',
                'pourcentage' => 'required|numeric'
            ]);
            $coupon->valid_until = $request->valid_until;
            $coupon->pourcentage = $request->pourcentage;
            $coupon->code = $request->code;
            $coupon->save();
            return redirect()
            ->route('coupons.index')
                ->with('success', 'Coupon modifié avec succès');
        }
        return redirect()
            ->route('coupons.index');
    }
}
