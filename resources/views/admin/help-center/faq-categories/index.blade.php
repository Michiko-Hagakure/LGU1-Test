@extends('layouts.admin')

@section('title', 'FAQ Categories')
@section('page-title', 'FAQ Categories')
@section('page-subtitle', 'Manage FAQ categories for the Help Center')

@section('page-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">FAQ Categories</h1>
        <p class="text-gray-600 mt-1">Manage FAQ categories for the Help Center</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ URL::signedRoute('admin.faq-categories.trash') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> View Trash
        </a>
        <a href="{{ URL::signedRoute('admin.faq-categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Category
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($categories->count() > 0)
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Icon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">FAQs Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($categories as $category)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $category->name }}</div>
                    @if($category->description)
                    <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $category->slug }}</td>
                <td class="px-6 py-4 text-2xl">{{ $category->icon ?? '' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $category->faqs_count }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $category->sort_order ?? 0 }}</td>
                <td class="px-6 py-4">
                    @if($category->is_active)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <a href="{{ URL::signedRoute('admin.faq-categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ URL::signedRoute('admin.faq-categories.destroy', $category->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(this)" class="text-red-600 hover:text-red-800">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="p-12 text-center text-gray-500">
        <i data-lucide="folder-open" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
        <p class="text-lg">No FAQ categories yet.</p>
        <a href="{{ URL::signedRoute('admin.faq-categories.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
            Create your first category
        </a>
    </div>
    @endif
</div>

<script>
function confirmDelete(button) {
    Swal.fire({
        title: 'Delete Category?',
        text: 'This will move the category to trash.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endsection
