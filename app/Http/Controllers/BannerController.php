<?php

namespace App\Http\Controllers;

use App\Models\banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = banners::Orderby('order_column', 'Asc')->get();
        return view('admin.banners.index')
            ->with('banners', $banners);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
            'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
            'show_btn' => 'nullable|string|max:255',
        ]);
        $banner = new banners();
        $banner->image = $request->file('image')->store('banner', 'public');
        $banner->image_mobile = $request->file('image_mobile')->store('banner', 'public');
        $banner->show_btn = $request->show_btn ? true : false;
        $banner->save();
        return redirect()
            ->route('banners.index')
            ->with('success', 'Nouveau bannière créé avec succès');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
            'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4048',
            'show_btn' => 'nullable|string|max:255',
        ]);
        $banner = banners::find($request->id);
        if ($request->image) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banner', 'public');
        }
        if ($request->image_mobile) {
            if ($banner->image_mobile) {
                Storage::disk('public')->delete($banner->image_mobile);
            }
            $banner->image_mobile = $request->file('image_mobile')->store('banner', 'public');
        }
        $banner->show_btn = $request->show_btn ? true : false;
        $banner->save();
        return redirect()
            ->route('banners.index')
            ->with('success', 'Bannière modifiée avec succès');
    }


    public function destroy(Request $request)
    {
        $banner = banners::find($request->id_banner);
        Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return redirect()
            ->route('banners.index')
            ->with('success', 'Bannière supprimée avec succès');
    }


    public function reorder(Request $request)
    {
        $order = $request->query('ids');
        $ids = explode(',', $order);
        foreach ($ids as $index => $id) {
            $category = banners::find($id);
            if ($category) {
                $category->order_column = $index + 1;
                $category->save();
            }
        }
        return response()->json(['success' => true]);
    }
}
