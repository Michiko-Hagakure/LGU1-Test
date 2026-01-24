<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published()->orderBy('published_at', 'desc');

        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $news = $query->paginate(12);
        $urgentNews = News::published()->urgent()->limit(3)->get();
        $featuredNews = News::published()->featured()->limit(3)->get();

        return view('citizen.news.index', compact('news', 'urgentNews', 'featuredNews'));
    }

    public function show($slug)
    {
        $newsItem = News::published()->where('slug', $slug)->firstOrFail();
        $newsItem->incrementViewCount();
        
        $relatedNews = News::published()
            ->where('category', $newsItem->category)
            ->where('id', '!=', $newsItem->id)
            ->limit(3)
            ->get();

        return view('citizen.news.show', compact('newsItem', 'relatedNews'));
    }
}
