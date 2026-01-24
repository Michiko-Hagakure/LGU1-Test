@extends('layouts.admin')

@section('title', 'FAQs')

@section('page-content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">FAQs</h1>
        <p class="text-gray-600 mt-1">Manage frequently asked questions</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.faqs.trash') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> View Trash
        </a>
        <a href="{{ route('admin.faqs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add FAQ
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($faqs->count() > 0)
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Helpful</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($faqs as $faq)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ Str::limit($faq->question, 60) }}</div>
                    <div class="text-sm text-gray-500">{{ Str::limit($faq->answer, 80) }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $faq->category->name }}</td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <span class="text-green-600">Helpful: {{ $faq->helpful_count }}</span>
                        <span class="text-red-600">Not Helpful: {{ $faq->not_helpful_count }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($faq->is_active)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(this)" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="px-6 py-4">
        {{ $faqs->links() }}
    </div>
    @else
    <div class="p-12 text-center text-gray-500">
        <i class="fas fa-question-circle text-6xl mb-4 text-gray-300"></i>
        <p class="text-lg">No FAQs yet.</p>
        <a href="{{ route('admin.faqs.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
            Create your first FAQ
        </a>
    </div>
    @endif
</div>

<script>
function confirmDelete(button) {
    Swal.fire({
        title: 'Delete FAQ?',
        text: 'This will move the FAQ to trash.',
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
