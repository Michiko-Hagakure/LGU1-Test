<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gov Admin | Fund Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 p-6 md:p-10 font-sans">
    <div class="max-w-7xl mx-auto bg-white shadow-2xl rounded-[2.5rem] overflow-hidden border border-white">
        
        <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black uppercase italic tracking-tighter">Energy Fund Management</h1>
                <p class="text-blue-400 text-[10px] font-bold uppercase tracking-widest">Official Approval Terminal</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] bg-blue-500/20 text-blue-300 px-3 py-1 rounded-full border border-blue-500/30 font-bold uppercase">System Active</span>
            </div>
        </div>
        
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b text-[10px] font-black text-slate-400 uppercase">
                <tr>
                    <th class="p-6">Requester & Seminar</th>
                    <th class="p-6">Purpose & Details</th>
                    <th class="p-6">Amount</th>
                    <th class="p-6">Admin Feedback</th>
                    <th class="p-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($requests as $req)
                <tr class="hover:bg-slate-50/50 transition" x-data="{ openLogistics: false }">
                    
                    <td class="p-6">
                        <div class="font-black text-slate-800 uppercase text-sm">
                            {{ $req->requester_name }}
                        </div>
                        <div class="text-[10px] text-blue-600 font-black tracking-widest uppercase mt-1 flex items-center gap-1">
                            <i class=" text-[8px]"></i> From: EE&CM
                        </div>

                        @if($req->seminar_info)
                        <div class="mt-4 p-3 bg-amber-50 border border-amber-100 rounded-2xl flex gap-3 items-center max-w-xs">
                            @if($req->seminar_image)
                                <img src="https://energy.local-government-unit-1-ph.com/{{ $req->seminar_image }}" 
                                     class="w-10 h-10 object-cover rounded-lg border-2 border-white shadow-sm">
                            @endif
                            <div>
                                <p class="text-[9px] font-black text-amber-600 uppercase leading-none">Linked Seminar:</p>
                                <p class="text-[10px] font-bold text-slate-700 leading-tight mt-1">{{ $req->seminar_info }}</p>
                            </div>
                        </div>
                        @endif
                    </td>

                    <td class="p-6">
                        <div class="font-bold text-slate-700 text-sm italic">{{ $req->purpose }}</div>
                        <button @click="openLogistics = true" class="mt-2 text-[10px] font-black uppercase text-blue-500 hover:text-blue-700 underline flex items-center gap-1">
                            <i class="fas fa-list-ul"></i> View Detailed Logistics
                        </button>

                        <div x-show="openLogistics" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" x-cloak>
                            
                            <div class="bg-white rounded-[2rem] max-w-lg w-full p-8 shadow-2xl relative border border-slate-100">
                                <div class="flex justify-between items-start mb-6">
                                    <h3 class="text-xl font-black uppercase italic tracking-tighter">Itemized Logistics</h3>
                                    <button @click="openLogistics = false" class="text-slate-400 hover:text-red-500 transition"><i class="fas fa-times"></i></button>
                                </div>
                                
                                <div class="bg-slate-50 p-6 rounded-2xl text-sm text-slate-600 leading-relaxed whitespace-pre-line border max-h-[60vh] overflow-y-auto font-medium">
                                    {{ $req->logistics }}
                                </div>
                                
                                <button @click="openLogistics = false" class="mt-6 w-full bg-slate-900 text-white p-4 rounded-xl font-bold uppercase italic tracking-widest hover:bg-slate-800 transition">
                                    Return to Terminal
                                </button>
                            </div>
                        </div>
                    </td>

                    <td class="p-6 font-black text-lg text-slate-900 italic">
                        ₱{{ number_format($req->amount, 2) }}
                    </td>
                    
                    <td class="p-6">
                        @if($req->status == 'pending')
                            <textarea name="feedback_input_{{ $req->id }}" id="fb_{{ $req->id }}" 
                                      placeholder="Note for requester..." 
                                      class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl text-[11px] font-medium outline-none focus:border-blue-500 focus:bg-white transition h-20"></textarea>
                        @else
                            <div class="text-[11px] italic text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="font-bold uppercase not-italic text-[9px] block mb-1 text-slate-400">Admin Feedback:</span>
                                {{ $req->feedback ?? 'No feedback provided.' }}
                            </div>
                        @endif
                    </td>

                    <td class="p-6">
                        @if($req->status == 'pending')
                        <div class="flex flex-col gap-2">
                            <button onclick="updateStatus({{ $req->id }}, 'Approved')" 
                                    class="bg-green-600 text-white px-4 py-3 rounded-xl text-[10px] font-black uppercase hover:bg-green-700 transition shadow-lg shadow-green-100">
                                Approve Request
                            </button>
                            <button onclick="updateStatus({{ $req->id }}, 'Rejected')" 
                                    class="bg-red-500 text-white px-4 py-3 rounded-xl text-[10px] font-black uppercase hover:bg-red-600 transition shadow-lg shadow-red-100">
                                Reject Request
                            </button>
                        </div>
                        @else
                        <div class="text-center">
                            <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border-2 {{ $req->status == 'Approved' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                                {{ $req->status }}
                            </span>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($requests) == 0)
        <div class="p-20 text-center">
            <i class="fas fa-folder-open text-slate-200 text-5xl mb-4"></i>
            <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">No pending fund requests found</p>
        </div>
        @endif
    </div>

    <form id="statusForm" action="{{ route('update.fund.status', '0') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="status" id="formStatus">
        <input type="hidden" name="feedback" id="formFeedback">
    </form>

    <script>
        function updateStatus(id, status) {
            const feedbackInput = document.getElementById('fb_' + id);
            const feedback = feedbackInput ? feedbackInput.value : '';
            
            if(status === 'Rejected' && feedback.trim() === '') {
                alert("Please provide a reason for rejection in the feedback box.");
                return;
            }

            if(confirm('Are you sure you want to ' + status + ' this request?')) {
                const form = document.getElementById('statusForm');
                form.action = form.action.replace('/0', '/' + id);
                document.getElementById('formStatus').value = status;
                document.getElementById('formFeedback').value = feedback;
                form.submit();
            }
        }
    </script>
</body>
</html>