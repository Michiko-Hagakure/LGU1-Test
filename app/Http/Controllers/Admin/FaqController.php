<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('category')
            ->orderBy('display_order')
            ->orderBy('question')
            ->paginate(20);
        
        return view('admin.help-center.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $categories = FaqCategory::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
        
        return view('admin.help-center.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = session('user_id');
        $validated['is_active'] = $request->has('is_active');

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully!');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $categories = FaqCategory::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
        
        return view('admin.help-center.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = session('user_id');
        $validated['is_active'] = $request->has('is_active');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully!');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->deleted_by = session('user_id');
        $faq->save();
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully!');
    }

    public function trash()
    {
        $faqs = Faq::onlyTrashed()
            ->with('category')
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);
        
        return view('admin.help-center.faqs.trash', compact('faqs'));
    }

    public function restore($id)
    {
        $faq = Faq::withTrashed()->findOrFail($id);
        $faq->restore();

        return redirect()->route('admin.faqs.trash')
            ->with('success', 'FAQ restored successfully!');
    }
}
