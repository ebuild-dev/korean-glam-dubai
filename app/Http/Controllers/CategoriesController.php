<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\Souscategorie;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function categories()
    {
        return view("admin.categories.categories");
    }

    public function souscategories()
    {
        return view("admin.categories.souscategories");
    }
    public function soussouscategories()
    {
        return view("admin.categories.soussouscategories");
    }

    public function marques(){
        return view("admin.marques.index");
    }


    public function reorder(Request $request)
    {
        $order = $request->query('ids');
        $ids = explode(',', $order);
        foreach ($ids as $index => $id) {
            $category = categories::find($id);
            if ($category) {
                $category->order_column = $index + 1;
                $category->save();
            }
        }
        return response()->json(['success' => true]);
    }

    public function sous_reorder(Request $request)
    {
        $order = $request->query('ids');
        $ids = explode(',', $order);
        foreach ($ids as $index => $id) {
            $category = Souscategorie::find($id);
            if ($category) {
                $category->order_column = $index + 1;
                $category->save();
            }
        }
        return response()->json(['success' => true]);
    }
    public function soussous_reorder(Request $request)
    {
        $order = $request->query('ids');
        $ids = explode(',', $order);

        foreach ($ids as $index => $id) {
            $soussous = \App\Models\SousSousCategorie::find($id);
            if ($soussous) {
                $soussous->order_column = $index + 1;
                $soussous->save();
            }
        }

        return response()->json(['success' => true]);
    }


}
