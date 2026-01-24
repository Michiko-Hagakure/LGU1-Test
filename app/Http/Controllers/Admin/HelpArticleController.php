<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HelpArticleController extends Controller
{
    public function index()
    {
        $articles = HelpArticle::orderBy('display_order')
            ->orderBy('title')
            ->paginate(20);
        
        return view('admin.help-center.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.help-center.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:help_articles,slug',
            'category' => 'required|in:getting_started,booking,payments,account,technical,policies',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'video_url' => 'nullable|url|max:500',
            'screenshots' => 'nullable|array',
            'screenshots.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'display_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle screenshot uploads
        $screenshotPaths = [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $screenshot) {
                $path = $screenshot->store('help-articles/screenshots', 'public');
                $screenshotPaths[] = $path;
            }
        }

        $validated['screenshots'] = !empty($screenshotPaths) ? json_encode($screenshotPaths) : null;
        $validated['created_by'] = session('user_id');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        HelpArticle::create($validated);

        return redirect()->route('admin.help-articles.index')
            ->with('success', 'Help Article created successfully!');
    }

    public function edit($id)
    {
        $article = HelpArticle::findOrFail($id);
        return view('admin.help-center.articles.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = HelpArticle::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:help_articles,slug,' . $id,
            'category' => 'required|in:getting_started,booking,payments,account,technical,policies',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'video_url' => 'nullable|url|max:500',
            'screenshots' => 'nullable|array',
            'screenshots.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'display_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle new screenshot uploads
        if ($request->hasFile('screenshots')) {
            $screenshotPaths = [];
            foreach ($request->file('screenshots') as $screenshot) {
                $path = $screenshot->store('help-articles/screenshots', 'public');
                $screenshotPaths[] = $path;
            }
            $validated['screenshots'] = json_encode($screenshotPaths);
        }

        $validated['updated_by'] = session('user_id');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $article->update($validated);

        return redirect()->route('admin.help-articles.index')
            ->with('success', 'Help Article updated successfully!');
    }

    public function destroy($id)
    {
        $article = HelpArticle::findOrFail($id);
        $article->deleted_by = session('user_id');
        $article->save();
        $article->delete();

        return redirect()->route('admin.help-articles.index')
            ->with('success', 'Help Article deleted successfully!');
    }

    public function trash()
    {
        $articles = HelpArticle::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);
        
        return view('admin.help-center.articles.trash', compact('articles'));
    }

    public function restore($id)
    {
        $article = HelpArticle::withTrashed()->findOrFail($id);
        $article->restore();

        return redirect()->route('admin.help-articles.trash')
            ->with('success', 'Help Article restored successfully!');
    }
}
