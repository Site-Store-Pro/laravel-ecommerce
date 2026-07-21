<div>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                    {{ __('Assigned Support Queue') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Manage and respond to your assigned issues</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-200 text-xs font-semibold text-indigo-700">
                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                Team Member Console Active
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Team Member Stats Grid -->
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-amber-50 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Queue</p>
                    <h3 class="text-3xl font-extrabold text-amber-600 mt-2">
                        {{ ($counts['open'] ?? 0) + ($counts['in_process'] ?? 0) + ($counts['assigned'] ?? 0) }}
                    </h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-emerald-50 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Resolved</p>
                    <h3 class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $counts['completed'] ?? 0 }}</h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-slate-50 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10H7v-2h10v2zm0-4H7V7h10v2zm0 8H7v-2h10v2z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tickets</p>
                    <h3 class="text-3xl font-extrabold text-slate-700 mt-2">{{ array_sum($counts) }}</h3>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm p-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="search" :value="__('Search Assigned Queue')" class="text-slate-500 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <x-text-input wire:model.live.debounce.300ms="search" id="search" class="block w-full ps-9 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" type="search" placeholder="Search title, description, customer name or email..." />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="statusFilter" :value="__('Status Filter')" class="text-slate-500 font-semibold" />
                        <select wire:model.live="statusFilter" id="statusFilter"
                                class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm">
                            <option value="">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tickets Table -->
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Ticket Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Assignee</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Replies</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Updated</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($tickets as $ticket)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col max-w-xs md:max-w-sm">
                                            <span class="font-semibold text-slate-900 text-sm truncate">
                                                <a href="{{ route('admin.tickets.show', $ticket) }}" wire:navigate>{{ $ticket->title }}</a>
                                            </span>
                                            <span class="text-xs text-slate-400 mt-1">#{{ $ticket->id }} · opened {{ $ticket->created_at->format('M j, Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-slate-700">{{ $ticket->user->name }}</span>
                                            <span class="text-xs text-slate-400">{{ $ticket->user->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        @if ($ticket->assignee)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                {{ $ticket->assignee->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-ticket-status-badge :status="$ticket->status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                        @if ($ticket->replies_count > 0)
                                            <span class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-600">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                {{ $ticket->replies_count }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">0 replies</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $ticket->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end">
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" wire:navigate 
                                               class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all">
                                                Manage
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="p-3 rounded-2xl bg-slate-50 text-slate-400 mb-4 border border-slate-100">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 4h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 15H4"/></svg>
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-base">All caught up</h4>
                                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">No tickets found matching the search criteria or status filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($tickets->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
