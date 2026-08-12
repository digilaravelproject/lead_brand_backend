@extends('admin.layout')

@section('title', 'Manage Leads')
@section('page_title', 'Leads Management')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Manage Leads</h1>
            <p class="text-xs text-slate-400 mt-0.5">View and manage client leads submitted by insurance agents.</p>
        </div>
        
        <!-- Search bar -->
        <form action="{{ route('admin.leads.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by lead, phone, or agent..." 
                       class="w-full bg-slate-900 border border-slate-800/80 rounded-xl py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-amber-550/15">
                    Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.leads.index') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-sm rounded-xl text-center transition-all border border-slate-700/40">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Leads Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6 w-[25%]">Submitted By (Agent)</th>
                        <th class="py-4 px-6 w-[20%]">Lead Full Name</th>
                        <th class="py-4 px-6">Phone Number</th>
                        <th class="py-4 px-6 text-center">Lead Status</th>
                        <th class="py-4 px-6 text-center">Active Status</th>
                        <th class="py-4 px-6 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="lead-row-{{ $lead->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $lead->id }}</td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-white">{{ $lead->user ? $lead->user->name : 'Unknown User' }}</div>
                                <div class="text-xs text-slate-400">{{ $lead->user ? $lead->user->email : 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6 font-semibold text-white">{{ $lead->full_name }}</td>
                            <td class="py-4 px-6 text-slate-450 font-mono text-xs">{{ $lead->phone_number }}</td>
                            <td class="py-4 px-6 text-center">
                                <select onchange="changeLeadStatus({{ $lead->id }}, this)" 
                                        class="bg-slate-950/60 border border-slate-800 rounded-xl py-1.5 px-3 text-xs text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all font-medium cursor-pointer">
                                    <option value="hot_lead" {{ $lead->status == 'hot_lead' ? 'selected' : '' }}>Hot Lead</option>
                                    <option value="appointment" {{ $lead->status == 'appointment' ? 'selected' : '' }}>Appointment</option>
                                    <option value="followup" {{ $lead->status == 'followup' ? 'selected' : '' }}>Follow Up</option>
                                    <option value="done" {{ $lead->status == 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleLeadStatus({{ $lead->id }}, this)" class="sr-only peer" {{ $lead->is_active == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <button onclick="viewLead({{ $lead->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-amber-600/10 text-slate-400 hover:text-amber-400 transition-colors"
                                        title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editLead({{ $lead->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteLead({{ $lead->id }}, '{{ addslashes($lead->full_name) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete Lead">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-500">No lead records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>

<!-- View Lead Modal -->
<div id="view-lead-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('view-lead-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Lead Details</h3>
            <button onclick="closeModal('view-lead-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-5 overflow-y-auto flex-1 scrollbar">
            <!-- Lead Profile Details -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Full Name</span>
                    <h4 class="text-sm font-bold text-white leading-snug" id="view-fullname">Loading...</h4>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Phone Number</span>
                    <h4 class="text-sm font-semibold text-slate-300 font-mono" id="view-phone">Loading...</h4>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-slate-800/80 pt-4">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Category Status</span>
                    <div>
                        <span id="view-leadstatus" class="px-2.5 py-1 text-xs font-bold rounded-full">Active</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Status</span>
                    <div>
                        <span id="view-activestatus" class="px-2.5 py-1 text-xs font-bold rounded-full">Active</span>
                    </div>
                </div>
            </div>

            <!-- Agent Creator Details -->
            <div class="space-y-3 border-t border-slate-800/80 pt-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Submitted By (Insurance Agent)</span>
                <div class="bg-slate-950/30 p-4 border border-slate-850 rounded-2xl space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-2xs font-semibold text-slate-500 block">Agent Name</span>
                            <span class="text-sm font-semibold text-slate-200" id="view-agent-name">Loading...</span>
                        </div>
                        <div>
                            <span class="text-2xs font-semibold text-slate-500 block">Agent Email</span>
                            <span class="text-sm font-semibold text-slate-200" id="view-agent-email">Loading...</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 border-t border-slate-800/40 pt-2.5">
                        <div>
                            <span class="text-2xs font-semibold text-slate-500 block">Agent Contact</span>
                            <span class="text-sm font-semibold text-slate-200 font-mono text-xs" id="view-agent-phone">Loading...</span>
                        </div>
                        <div>
                            <span class="text-2xs font-semibold text-slate-500 block">Agent Designation</span>
                            <span class="text-sm font-semibold text-slate-200" id="view-agent-destination">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4 border-t border-slate-800/80 pt-4 text-xs">
                <div>
                    <span class="text-slate-500">Created Date:</span>
                    <span class="text-slate-400 font-semibold block mt-0.5" id="view-created-at">Loading...</span>
                </div>
                <div>
                    <span class="text-slate-500">Last Modified:</span>
                    <span class="text-slate-400 font-semibold block mt-0.5" id="view-updated-at">Loading...</span>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end flex-shrink-0">
            <button onclick="closeModal('view-lead-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl border border-slate-700/40">
                Close View
            </button>
        </div>
    </div>
</div>

<!-- Edit Lead Details Modal -->
<div id="edit-lead-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-lead-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify Lead Details</h3>
            <button onclick="closeModal('edit-lead-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="edit-lead-form" action="" method="POST" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div>
                    <label for="edit-fullname" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Lead Name</label>
                    <input type="text" name="full_name" id="edit-fullname" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="edit-phone" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="text" name="phone_number" id="edit-phone" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Category Status</label>
                        <select name="status" id="edit-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm cursor-pointer">
                            <option value="hot_lead">Hot Lead</option>
                            <option value="appointment">Appointment</option>
                            <option value="followup">Follow Up</option>
                            <option value="done">Done</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit-isactive" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Active Status</label>
                        <select name="is_active" id="edit-isactive" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm cursor-pointer">
                            <option value="1">Active / Display</option>
                            <option value="0">Deactivated / Hidden</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('edit-lead-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                    Save Updates
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Lead Confirmation Modal -->
<div id="delete-lead-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('delete-lead-modal')"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
        <div class="p-6 text-center space-y-4">
            <div class="h-14 w-14 bg-red-500/10 border border-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white">Permanently Remove Lead?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete this lead record? This action is permanent and cannot be reversed.</p>
            <div class="text-xs text-slate-500 italic p-3 bg-slate-950/30 border border-slate-850 rounded-xl" id="delete-lead-placeholder"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-lead-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Abort
            </button>
            <form id="delete-lead-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30">
                    Delete Lead
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/leads') }}";

    function viewLead(id) {
        document.getElementById('view-fullname').textContent = 'Loading...';
        document.getElementById('view-phone').textContent = 'Loading...';
        document.getElementById('view-leadstatus').className = 'hidden';
        document.getElementById('view-activestatus').className = 'hidden';
        
        document.getElementById('view-agent-name').textContent = 'Loading...';
        document.getElementById('view-agent-email').textContent = 'Loading...';
        document.getElementById('view-agent-phone').textContent = 'Loading...';
        document.getElementById('view-agent-destination').textContent = 'Loading...';

        document.getElementById('view-created-at').textContent = 'Loading...';
        document.getElementById('view-updated-at').textContent = 'Loading...';

        openModal('view-lead-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(lead => {
                document.getElementById('view-fullname').textContent = lead.full_name;
                document.getElementById('view-phone').textContent = lead.phone_number;
                
                // Formatted dates
                const createdDate = new Date(lead.created_at);
                const updatedDate = new Date(lead.updated_at);
                document.getElementById('view-created-at').textContent = createdDate.toLocaleString();
                document.getElementById('view-updated-at').textContent = updatedDate.toLocaleString();

                // Category status badge
                const statusBadge = document.getElementById('view-leadstatus');
                statusBadge.classList.remove('hidden');
                
                let statusText = 'Hot Lead';
                let statusClass = 'bg-rose-500/10 text-rose-450 border border-rose-500/20';
                
                if (lead.status === 'appointment') {
                    statusText = 'Appointment';
                    statusClass = 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20';
                } else if (lead.status === 'followup') {
                    statusText = 'Follow Up';
                    statusClass = 'bg-amber-500/10 text-amber-450 border border-amber-500/20';
                } else if (lead.status === 'done') {
                    statusText = 'Done';
                    statusClass = 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                }
                
                statusBadge.textContent = statusText;
                statusBadge.className = `px-2.5 py-1 text-xs font-bold rounded-full ${statusClass}`;

                // Active status badge
                const activeBadge = document.getElementById('view-activestatus');
                activeBadge.classList.remove('hidden');
                if (lead.is_active == 1) {
                    activeBadge.textContent = 'Active / Visible';
                    activeBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                } else {
                    activeBadge.textContent = 'Inactive / Deactivated';
                    activeBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-slate-850 text-slate-400 border border-slate-750';
                }

                // Creator agent details
                if (lead.user) {
                    document.getElementById('view-agent-name').textContent = lead.user.name;
                    document.getElementById('view-agent-email').textContent = lead.user.email;
                    document.getElementById('view-agent-phone').textContent = lead.user.phone_number || 'No Phone Registered';
                    document.getElementById('view-agent-destination').textContent = lead.user.destination || 'Insurance Agent';
                } else {
                    document.getElementById('view-agent-name').textContent = 'Unknown / Guest';
                    document.getElementById('view-agent-email').textContent = 'N/A';
                    document.getElementById('view-agent-phone').textContent = 'N/A';
                    document.getElementById('view-agent-destination').textContent = 'N/A';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('view-fullname').textContent = 'Error loading lead details';
            });
    }

    function editLead(id) {
        document.getElementById('edit-lead-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-fullname').value = '';
        document.getElementById('edit-phone').value = '';
        document.getElementById('edit-status').value = 'hot_lead';
        document.getElementById('edit-isactive').value = '1';

        openModal('edit-lead-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(lead => {
                document.getElementById('edit-fullname').value = lead.full_name;
                document.getElementById('edit-phone').value = lead.phone_number;
                document.getElementById('edit-status').value = lead.status;
                document.getElementById('edit-isactive').value = lead.is_active;
            })
            .catch(err => console.error(err));
    }

    function toggleLeadStatus(id, checkbox) {
        fetch(`${baseUrl}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                checkbox.checked = !checkbox.checked;
                alert('Failed to toggle active status.');
            }
        })
        .catch(err => {
            console.error(err);
            checkbox.checked = !checkbox.checked;
            alert('An error occurred while communicating with the server.');
        });
    }

    function changeLeadStatus(id, selectElement) {
        const newStatus = selectElement.value;
        
        fetch(`${baseUrl}/${id}/change-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Failed to update lead status category.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while communicating with the server.');
        });
    }

    function confirmDeleteLead(id, fullname) {
        document.getElementById('delete-lead-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-lead-placeholder').textContent = `"${fullname}"`;
        openModal('delete-lead-modal');
    }
</script>
@endsection
