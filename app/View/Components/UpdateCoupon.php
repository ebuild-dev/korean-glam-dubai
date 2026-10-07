<?php

namespace App\View\Components;

use App\Models\Coupon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UpdateCoupon extends Component
{
    /**
     * Create a new component instance.
     */
    public $coupon;
    public function __construct($id)
    {
        $this->coupon = Coupon::find($id);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.update-coupon');
    }
}
