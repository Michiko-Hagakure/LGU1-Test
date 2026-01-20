@extends('layouts.admin')

@section('page-content')    
<div class="p-gr-md bg-gray-50/50 min-h-screen">
    <div class="mb-gr-lg flex justify-between items-center">
        <div>
            <h1 class="text-heading-lg font-bold text-emerald-900">System Audit Trail</h1>
            <p class="text-small text-gray-500 font-medium">Review all administrative logs and security activities.</p>
        </div>
        <div class="flex space-x-3">
            <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-small font-bold hover:bg-gray-50 shadow-sm transition-all flex items-center">
                <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
            </button>
            <a href="{{ route('admin.audit.export') }}" class="px-6 py-2 bg-lgu-secondary text-white rounded-lg hover:opacity-90 transition-all font-semibold flex items-center">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                <span>Export PDF</span>
            </a>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-emerald-900">
                    <tr class="text-[10px] font-black text-emerald-100 uppercase tracking-[0.2em]">
                        <th class="px-8 py-5">Administrator</th>
                        <th class="px-6 py-5">Action Type</th>
                        <th class="px-6 py-5">System Module</th>
                        <th class="px-6 py-5">IP Address</th>
                        <th class="px-6 py-5">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-xs uppercase">
                                        {{ substr($log->user_name, 0, 2) }}
                                    </div>
                                    <div class="flex flex-col leading-tight">
                                        <span class="text-small font-bold text-gray-900">{{ $log->user_name }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium tracking-tight">Administrator</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border 
                                    {{ str_contains(strtolower($log->action), 'delete') ? 'bg-red-50 text-red-600 border-red-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-small text-gray-600 font-bold uppercase tracking-tighter">
                                {{ $log->model }}
                            </td>
                            <td class="px-6 py-4 font-mono text-[11px] text-gray-400 font-medium">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col leading-tight">
                                    <span class="text-small font-bold text-gray-800 tracking-tighter">
                                        {{ $log->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase">
                                        {{ $log->created_at->format('h:i A') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="database" class="w-12 h-12 text-gray-200 mb-4"></i>
                                    <p class="text-gray-400 font-medium italic">No activity logs recorded yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Siguraduhing mag-re-render ang icons
    lucide.createIcons();
</script>
@endsection