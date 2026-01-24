<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\Faq;
use App\Models\HelpArticle;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    public function index()
    {
        $faqCategories = FaqCategory::active()->ordered()->with(['faqs' => function($query) {
            $query->published()->ordered();
        }])->get();

        $popularArticles = HelpArticle::published()
            ->orderBy('view_count', 'desc')
            ->limit(6)
            ->get();

        return view('citizen.help-center.index', compact('faqCategories', 'popularArticles'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('q');

        $faqs = Faq::published()
            ->where(function($query) use ($searchTerm) {
                $query->where('question', 'like', '%' . $searchTerm . '%')
                      ->orWhere('answer', 'like', '%' . $searchTerm . '%');
            })
            ->with('category')
            ->get();

        $articles = HelpArticle::published()
            ->where(function($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('content', 'like', '%' . $searchTerm . '%')
                      ->orWhere('excerpt', 'like', '%' . $searchTerm . '%');
            })
            ->get();

        return view('citizen.help-center.search', compact('faqs', 'articles', 'searchTerm'));
    }

    public function article($slug)
    {
        $article = HelpArticle::published()->where('slug', $slug)->firstOrFail();
        $article->incrementViewCount();

        $relatedArticles = HelpArticle::published()
            ->byCategory($article->category)
            ->where('id', '!=', $article->id)
            ->limit(3)
            ->get();

        return view('citizen.help-center.article', compact('article', 'relatedArticles'));
    }

    public function articles(Request $request)
    {
        $query = HelpArticle::published()->ordered();

        if ($request->has('category') && $request->category != 'all') {
            $query->byCategory($request->category);
        }

        $articles = $query->get();

        return view('citizen.help-center.articles', compact('articles'));
    }

    public function markHelpful(Request $request, $type, $id)
    {
        if ($type === 'faq') {
            $item = Faq::findOrFail($id);
        } else {
            $item = HelpArticle::findOrFail($id);
        }

        if ($request->input('helpful') === 'yes') {
            $item->markHelpful();
        } else {
            $item->markNotHelpful();
        }

        return response()->json(['success' => true]);
    }
}
