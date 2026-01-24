<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug',
            'category' => 'required|in:general,facility_update,policy_change,maintenance,emergency',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_at' => 'required|date',
            'is_featured' => 'boolean',
            'is_urgent' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('news', 'public');
        }

        $validated['created_by'] = session('user_id');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_urgent'] = $request->has('is_urgent');
        $validated['is_active'] = $request->has('is_active');

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article created successfully!');
    }

    public function edit($id)
    {
        $newsItem = News::findOrFail($id);
        return view('admin.news.edit', compact('newsItem'));
    }

    public function update(Request $request, $id)
    {
        $newsItem = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug,' . $id,
            'category' => 'required|in:general,facility_update,policy_change,maintenance,emergency',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_at' => 'required|date',
            'is_featured' => 'boolean',
            'is_urgent' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('news', 'public');
        }

        $validated['updated_by'] = session('user_id');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_urgent'] = $request->has('is_urgent');
        $validated['is_active'] = $request->has('is_active');

        $newsItem->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'News article updated successfully!');
    }

    public function destroy($id)
    {
        $newsItem = News::findOrFail($id);
        $newsItem->deleted_by = session('user_id');
        $newsItem->save();
        $newsItem->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News article deleted successfully!');
    }

    public function trash()
    {
        $news = News::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);
        
        return view('admin.news.trash', compact('news'));
    }

    public function restore($id)
    {
        $newsItem = News::withTrashed()->findOrFail($id);
        $newsItem->restore();

        return redirect()->route('admin.news.trash')
            ->with('success', 'News article restored successfully!');
    }
}
