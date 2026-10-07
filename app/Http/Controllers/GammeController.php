<?php

namespace App\Http\Controllers;

use App\Models\Gamme;
use Illuminate\Http\Request;

class GammeController extends Controller
{
    public function index()
    {
        return view("admin.gammes.index");
    }
    public function reorder(Request $request)
    {
        $ids = explode(',', $request->ids);

        foreach ($ids as $index => $id) {
            Gamme::where('id', $id)->update(['order_column' => $index + 1]);
        }

        return response()->json(['status' => 'ok']);
    }

}
