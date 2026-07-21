<div>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.tickets') : route('admin.assigned-tickets') }}" wire:navigate class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                        {{ $ticket->title }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-0.5">Ticket #{{ $ticket->id }} · Customer: {{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
                </div>
            </div>
            <x-ticket-status-badge :status="$ticket->status" class="px-3.5 py-1 text-sm shadow-sm" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Conversation Area -->
                <div class="lg:col-span-2 space-y-6">
                    @if (session('status'))
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center gap-2 shadow-sm animate-fade-in">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Original Request Details -->
                    <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-6 sm:p-8">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold">
                                    {{ substr($ticket->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $ticket->user->name }}</h4>
                                    <p class="text-xs text-slate-400">Original Request</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $ticket->description }}</p>
                        @include('partials.ticket-attachments', ['attachments' => $ticket->attachments])
                    </div>

                    <!-- Conversation Thread -->
                    <div class="space-y-6">
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Replies & Conversation
                        </h3>
                        
                        @forelse ($ticket->replies as $reply)
                            @php
                                $isSupport = $reply->user?->isStaff();
                            @endphp
                            <div class="bg-white border shadow-sm rounded-2xl p-6 sm:p-8 transition-all hover:shadow-md {{ $isSupport ? 'border-indigo-150 bg-indigo-50/20' : 'border-slate-200/70' }}">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100/80 mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl flex items-center justify-center font-bold text-sm {{ $isSupport ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                                            {{ substr($reply->authorLabel(), 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-slate-800 text-sm">{{ $reply->authorLabel() }}</h4>
                                                @if ($isSupport)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-100 text-indigo-700 text-[10px] font-bold tracking-wider uppercase">
                                                        Support Agent
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-400">{{ $reply->created_at->format('M j, Y g:i A') }} · via {{ ucfirst($reply->via) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $reply->body }}</p>
                                @include('partials.ticket-attachments', ['attachments' => $reply->attachments])
                            </div>
                        @empty
                            <div class="border border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-400 text-sm">
                                No replies recorded yet.
                            </div>
                        @endforelse
                    </div>

                    <!-- Admin Post Reply Form -->
                    <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-6 sm:p-8">
                        <h3 class="font-bold text-slate-800 text-lg">Send Reply to Customer</h3>
                        <form wire:submit="reply" class="mt-4 space-y-4">
                            @if (!empty($aiResponse))
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 mb-4 space-y-2 animate-fade-in">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            AI Suggested Response
                                        </span>
                                        <button type="button" wire:click="copyAiResponse" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors border border-indigo-150 shadow-sm">
                                            Copy to Reply
                                        </button>
                                    </div>
                                    <textarea readonly rows="4" class="block w-full rounded-xl border-slate-200 bg-white text-sm text-slate-600 shadow-sm focus:ring-0 focus:border-slate-200">{{ $aiResponse }}</textarea>
                                </div>
                            @endif

                            <div class="flex gap-4 items-start">
                                <div class="flex-1">
                                    <textarea wire:model="body" rows="5"
                                              class="block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                              placeholder="Type your reply to customer..."></textarea>
                                    <x-input-error :messages="$errors->get('body')" class="mt-2 text-xs" />
                                </div>
                                @if ($showAiButton)
                                    <button type="button" wire:click="generateAiResponse" wire:loading.attr="disabled"
                                            class="inline-flex flex-col items-center justify-center px-4 rounded-xl border border-indigo-200 bg-indigo-50/50 hover:bg-indigo-50 text-indigo-600 transition-all shadow-sm self-stretch group min-w-[80px]"
                                            title="Generate AI Response">
                                        <span wire:loading.remove wire:target="generateAiResponse" class="flex flex-col items-center gap-1">
                                            <svg class="w-5 h-5 text-indigo-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            <span class="text-[10px] font-bold uppercase tracking-wider">AI Assist</span>
                                        </span>
                                        <span wire:loading wire:target="generateAiResponse">
                                            <svg class="animate-spin h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                @endif
                            </div>

                            <!-- Attachments -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Upload Files (optional)</label>
                                <input wire:model="attachments" type="file" multiple
                                       accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.txt,.doc,.docx"
                                       class="block w-full text-xs text-slate-500 border border-slate-200/80 rounded-xl bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 file:cursor-pointer cursor-pointer shadow-sm" />
                                
                                @if (!empty($attachments))
                                    <div class="mt-2 text-xs text-slate-500">
                                        Files selected: {{ count($attachments) }}
                                    </div>
                                @endif
                                <x-input-error :messages="$errors->get('attachments.*')" class="mt-2 text-xs" />
                            </div>

                            <div class="flex justify-end pt-2 border-t border-slate-100">
                                <x-primary-button wire:loading.attr="disabled" class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500">
                                    <span wire:loading.remove wire:target="reply">Send Message</span>
                                    <span wire:loading wire:target="reply" class="flex items-center gap-1.5">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sending...
                                    </span>
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Side - Admin Actions & Settings Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-6 space-y-5">
                        <h3 class="font-bold text-slate-800 text-base pb-3 border-b border-slate-100">Manage Ticket</h3>
                        
                        <form wire:submit="updateStatus" class="space-y-4">
                            <div>
                                <x-input-label for="status" :value="__('Status')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                <select wire:model="status" id="status"
                                        class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm">
                                    @foreach ($statuses as $statusOption)
                                        <option value="{{ $statusOption->value }}">{{ $statusOption->label() }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="assigned_to" :value="__('Assign to Staff')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                <select wire:model="assigned_to" id="assigned_to"
                                        class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm">
                                    <option value="">Unassigned</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('assigned_to')" class="mt-1 text-xs" />
                            </div>

                            <div class="pt-2">
                                <x-primary-button class="w-full justify-center rounded-xl py-2.5">Update Settings</x-primary-button>
                            </div>
                        </form>
                        
                        <p class="text-[10px] text-slate-400 text-center leading-relaxed">Customers are automatically notified by email when you update the ticket's status (except if marked as Closed).</p>
                    </div>

                    <!-- Customer Info Sidebar Card -->
                    <div class="bg-slate-50 border border-slate-200 shadow-sm rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-800 text-base pb-2 border-b border-slate-200">Customer Details</h3>
                        
                        <div class="space-y-3 text-sm text-slate-700">
                            <div>
                                <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Name</label>
                                <span class="font-semibold text-slate-800">{{ $ticket->user->name }}</span>
                            </div>
                            <div>
                                <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Email</label>
                                <a href="mailto:{{ $ticket->user->email }}" class="text-indigo-600 hover:text-indigo-700 font-semibold block break-all">{{ $ticket->user->email }}</a>
                            </div>
                            <div>
                                <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Inbound Reply Token</label>
                                <span class="text-xs font-mono bg-white border border-slate-200 p-2 rounded-lg block select-all break-all mt-1">
                                    {{ $ticket->replyEmailAddress() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Secure Public Link Card -->
                    <div class="bg-gradient-to-tr from-slate-900 to-indigo-950 text-slate-100 shadow-lg shadow-indigo-900/10 rounded-2xl p-6 relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 text-indigo-500/10 pointer-events-none">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
                        </div>
                        <h3 class="font-bold text-white text-base">Secure Public Link</h3>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">This URL allows the user to securely access their ticket without entering password.</p>
                        
                        <div class="mt-4" x-data="{ copied: false, url: '{{ $ticket->publicUrl() }}' }">
                            <input readonly type="text" :value="url" class="block w-full rounded-xl bg-white/10 border-0 focus:ring-0 text-xs text-slate-200 py-2.5 px-3 select-all font-mono" />
                            <button @click="navigator.clipboard.writeText(url); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="mt-3 w-full inline-flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/15 px-4 py-2 text-xs font-semibold text-white border border-white/5 transition-all">
                                <span x-show="!copied">Copy Link</span>
                                <span x-show="copied" class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Copied!
                                </span>
                            </button>
                        </div>
                    </div>

                    @if (auth()->user()->isAdmin())
                    {{-- Danger Zone --}}
                    <div class="bg-white border border-rose-200 shadow-sm rounded-2xl p-6"
                         x-data="{ confirming: false }">
                        <h3 class="font-bold text-rose-700 text-base pb-3 border-b border-rose-100 mb-4">Danger Zone</h3>

                        <div x-show="!confirming">
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                Permanently delete this ticket. This action cannot be undone and will remove all replies and attachments.
                            </p>
                            <button @click="confirming = true"
                                    class="w-full inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold border border-rose-200 text-rose-600 hover:bg-rose-50 transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete Ticket
                            </button>
                        </div>

                        <div x-show="confirming" x-cloak class="space-y-3">
                            <p class="text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-xl p-3 text-center leading-relaxed">
                                Are you sure? This cannot be undone.
                            </p>
                            <div class="flex gap-2">
                                <button @click="confirming = false"
                                        class="flex-1 inline-flex items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all">
                                    Cancel
                                </button>
                                <button wire:click="deleteTicket" wire:loading.attr="disabled"
                                        class="flex-1 inline-flex items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold bg-rose-600 hover:bg-rose-500 text-white transition-all">
                                    <span wire:loading.remove wire:target="deleteTicket">Confirm Delete</span>
                                    <span wire:loading wire:target="deleteTicket" class="flex items-center gap-1">
                                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Deleting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
