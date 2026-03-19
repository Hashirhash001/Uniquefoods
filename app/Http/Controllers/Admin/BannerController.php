<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'description'      => 'nullable|string|max:500',
            'button_text'      => 'nullable|string|max:50',
            'button_link'      => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'background_color' => 'nullable|string|max:20',
            'text_color'       => 'nullable|string|max:20',
            'title_color'        => 'nullable|string|max:20',
            'subtitle_color'     => 'nullable|string|max:20',
            'description_color'  => 'nullable|string|max:20',
            'subtitle_bg_color'  => 'nullable|string|max:20',
            'sort_order'       => 'nullable|integer',
            'is_active'        => 'nullable|boolean',
        ]);

        $validated['sort_order'] ??= (Banner::max('sort_order') ?? 0) + 1;
        $validated['is_active']    = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($validated);

        // ✅ Clear banner cache so homepage reflects new banner immediately
        Cache::forget('active_banners');

        return response()->json([
            'success' => true,
            'message' => 'Banner created successfully',
        ]);
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'description'      => 'nullable|string|max:500',
            'button_text'      => 'nullable|string|max:50',
            'button_link'      => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'background_color' => 'nullable|string|max:20',
            'text_color'       => 'nullable|string|max:20',
            'title_color'        => 'nullable|string|max:20',
            'subtitle_color'     => 'nullable|string|max:20',
            'description_color'  => 'nullable|string|max:20',
            'subtitle_bg_color'  => 'nullable|string|max:20',
            'sort_order'       => 'nullable|integer',
            'is_active'        => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($validated);

        // ✅ Clear cache so homepage reflects updated banner immediately
        Cache::forget('active_banners');

        return response()->json([
            'success' => true,
            'message' => 'Banner updated successfully',
        ]);
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        // ✅ Clear cache so deleted banner disappears from homepage immediately
        Cache::forget('active_banners');

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully',
        ]);
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        // ✅ Clear cache so toggle status reflects on homepage immediately
        Cache::forget('active_banners');

        return response()->json([
            'success'   => true,
            'is_active' => $banner->is_active,
            'message'   => 'Banner status updated',
        ]);
    }
}
