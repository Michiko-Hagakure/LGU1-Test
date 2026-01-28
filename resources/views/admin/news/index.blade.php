@extends('layouts.admin')

@section('title', 'News Management')
@section('page-title', 'News Management')
@section('page-subtitle', 'Manage news articles and announcements')

@section('page-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">News Management</h1>
        <p class="text-gray-600 mt-1">Manage news articles and announcements</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.news.trash') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> View Trash
        </a>
        <a href="{{ route('admin.news.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add News
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($news->count() > 0)
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Published</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($news as $newsItem)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $newsItem->title }}</div>
                    <div class="text-sm text-gray-500">{{ Str::limit($newsItem->excerpt ?? strip_tags($newsItem->content), 60) }}</div>
                    <div class="flex gap-2 mt-1">
                        @if($newsItem->is_featured)
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Featured</span>
                        @endif
                        @if($newsItem->is_urgent)
                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Urgent</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ ucfirst(str_replace('_', ' ', $newsItem->category)) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $newsItem->published_at ? \Carbon\Carbon::parse($newsItem->published_at)->format('M d, Y') : 'Draft' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $newsItem->view_count }}</td>
                <td class="px-6 py-4">
                    @if($newsItem->is_active)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.news.edit', $newsItem->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.news.destroy', $newsItem->id) }}" method="POST" class="inline">
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

    <div class="px-6 py-4">
        {{ $news->links() }}
    </div>
    @else
    <div class="p-12 text-center text-gray-500">
        <i data-lucide="newspaper" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
        <p class="text-lg">No news articles yet.</p>
        <a href="{{ route('admin.news.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
            Create your first article
        </a>
    </div>
    @endif
</div>

<script>
function confirmDelete(button) {
    Swal.fire({
        title: 'Delete News Article?',
        text: 'This will move the article to trash.',
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
