<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::withCount('faqs')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('admin.help-center.faq-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.help-center.faq-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:faq_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = session('user_id');
        $validated['is_active'] = $request->has('is_active');

        FaqCategory::create($validated);

        return redirect()->route('admin.faq-categories.index')
            ->with('success', 'FAQ Category created successfully!');
    }

    public function edit($id)
    {
        $category = FaqCategory::findOrFail($id);
        return view('admin.help-center.faq-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = FaqCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:faq_categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = session('user_id');
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.faq-categories.index')
            ->with('success', 'FAQ Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = FaqCategory::findOrFail($id);
        
        // Check if category has FAQs
        if ($category->faqs()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing FAQs. Please delete or reassign the FAQs first.');
        }

        $category->deleted_by = session('user_id');
        $category->save();
        $category->delete();

        return redirect()->route('admin.faq-categories.index')
            ->with('success', 'FAQ Category deleted successfully!');
    }

    public function trash()
    {
        $categories = FaqCategory::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('admin.help-center.faq-categories.trash', compact('categories'));
    }

    public function restore($id)
    {
        $category = FaqCategory::withTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.faq-categories.trash')
            ->with('success', 'FAQ Category restored successfully!');
    }
}
