<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{    
    public function index()
    {
        $advertisements = Advertisement::latest()->get();
        return view('admin.advertisements.index', compact('advertisements'));
    }
    
    public function create()
    {
        return view('admin.advertisements.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'link_url' => 'nullable|url',
            'position' => 'required|in:top_header,home_middle,article_top,article_middle,article_bottom,sidebar',
            'is_active' => 'boolean'
        ]);

        $imagePath = $request->file('image')->store('ads', 'public');

        Advertisement::create([
            'title' => $request->title,
            'image_path' => $imagePath,
            'link_url' => $request->link_url,
            'position' => $request->position,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.advertisements.index')->with('success', 'Iklan berhasil ditambahkan!');
    }
    
    public function edit(Advertisement $advertisement)
    {
        return view('admin.advertisements.edit', compact('advertisement'));
    }
    
    public function update(Request $request, Advertisement $advertisement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'link_url' => 'nullable|url',
            'position' => 'required|in:top_header,home_middle,article_top,article_middle,article_bottom,sidebar',
            'is_active' => 'boolean'
        ]);

        $data = $request->only(['title', 'link_url', 'position']);
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($advertisement->image_path)) {
                Storage::disk('public')->delete($advertisement->image_path);
            }
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')->with('success', 'Iklan berhasil diperbarui!');
    }
    
    public function destroy(Advertisement $advertisement)
    {
        if (Storage::disk('public')->exists($advertisement->image_path)) {
            Storage::disk('public')->delete($advertisement->image_path);
        }
        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('success', 'Iklan berhasil dihapus!');
    }
}