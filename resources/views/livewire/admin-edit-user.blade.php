<div>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users') }}" wire:navigate
                   class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-slate-900 leading-tight">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border {{ $user->role_id->badgeClasses() }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $user->role_id->dotClasses() }}"></span>
                {{ $user->role_id->label() }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">

                {{-- Left: Edit Form --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Flash Messages --}}
                    <x-toast-alert />

                    {{-- Profile Details Form --}}
                    <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-6 sm:p-8">
                        <h3 class="font-bold text-slate-800 text-lg pb-4 border-b border-slate-100 mb-6">Profile Details</h3>
                        <form wire:submit="save" class="space-y-5">

                            <div>
                                <x-input-label for="name" :value="__('Full Name')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                <x-text-input wire:model="form.name" id="name" type="text"
                                              class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                <x-input-error :messages="$errors->get('form.name')" class="mt-1.5 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email Address')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                <x-text-input wire:model="form.email" id="email" type="email"
                                              class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                <x-input-error :messages="$errors->get('form.email')" class="mt-1.5 text-xs" />
                            </div>

                            @php
                                $roleVal = is_object($user->role_id) ? $user->role_id->value : (int) $user->role_id;
                            @endphp
                            @if(in_array($roleVal, [1, 2]))
                                <div class="pt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="form.opt_in" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700">Mailing List Opt-in Status</span>
                                    </label>
                                    <p class="text-[11px] text-slate-400 mt-1">Allows administrators to toggle the customer's opt-in status and automatically syncs with third-party providers (Mailchimp, Constant Contact, Klaviyo).</p>
                                </div>
                            @endif

                            <div class="pt-4 border-t border-slate-100">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Change Password <span class="normal-case font-normal text-slate-400">(leave blank to keep current)</span></p>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="password" :value="__('New Password')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                        <x-text-input wire:model="form.password" id="password" type="password"
                                                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                      placeholder="Min. 8 characters" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('form.password')" class="mt-1.5 text-xs" />
                                    </div>
                                    <div>
                                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                        <x-text-input wire:model="form.password_confirmation" id="password_confirmation" type="password"
                                                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                      placeholder="Repeat new password" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('form.password_confirmation')" class="mt-1.5 text-xs" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-2 border-t border-slate-100">
                                <x-primary-button wire:loading.attr="disabled"
                                                  class="rounded-xl px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500">
                                    <span wire:loading.remove wire:target="save">Save Changes</span>
                                    <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Saving...
                                    </span>
                                </x-primary-button>
                            </div>

                        </form>
                    </div>

                </div>

                {{-- Right Sidebar --}}
                <div class="space-y-6">

                    {{-- Role & Access --}}
                    <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-800 text-base pb-3 border-b border-slate-100">Role & Access</h3>

                        <div class="space-y-2">
                            @foreach ($roles as $role)
                                <label class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-all
                                    {{ $selectedRole == $role->value ? 'border-indigo-300 bg-indigo-50/60' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}
                                    {{ $user->id === auth()->id() ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <input wire:model.live="selectedRole" type="radio" name="selectedRole" value="{{ $role->value }}"
                                           class="mt-0.5 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }} />
                                    <div>
                                        <span class="text-sm font-semibold">
                                            {{ $role->label() }}
                                        </span>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $role->description() }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @if ($user->id === auth()->id())
                            <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-xl p-2.5 text-center">
                                You cannot change your own role.
                            </p>
                        @else
                            <button wire:click="changeRole" wire:loading.attr="disabled"
                                    class="w-full inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition-all shadow-sm">
                                <span wire:loading.remove wire:target="changeRole">Update Role</span>
                                <span wire:loading wire:target="changeRole" class="flex items-center gap-1.5">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Updating...
                                </span>
                            </button>
                        @endif
                    </div>

                    {{-- Preferred Customer Discount --}}
                    <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-800 text-base pb-3 border-b border-slate-100">Preferred Discount</h3>
                        
                        <div>
                            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-2">Select Active Preferred Discount</label>
                            <select wire:model="preferred_discount_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-xs text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-semibold">
                                <option value="">-- No Preferred Discount --</option>
                                @foreach($preferredDiscounts as $disc)
                                    <option value="{{ $disc->id }}">{{ $disc->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-[10px] text-slate-400 block mt-1.5 leading-relaxed">
                                Note: This discount will automatically apply to checkout reviews if the customer is logged in.
                            </span>
                        </div>
                    </div>

                    {{-- Account Info --}}
                    <div class="bg-slate-50 border border-slate-200 shadow-sm rounded-2xl p-6 space-y-4">
                        <h3 class="font-bold text-slate-800 text-base pb-2 border-b border-slate-200">Account Info</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Member Since</label>
                                <span class="font-semibold text-slate-800">{{ $user->created_at->format('M j, Y') }}</span>
                            </div>
                            <div>
                                <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Email Verified</label>
                                @if ($user->email_verified_at)
                                    <span class="font-semibold text-emerald-600">{{ $user->email_verified_at->format('M j, Y') }}</span>
                                @else
                                    <span class="font-semibold text-amber-500">Not verified</span>
                                @endif
                            </div>
                            <div>
                                <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Auth Provider</label>
                                <span class="font-semibold text-slate-800">{{ $user->provider ? ucfirst($user->provider) : 'Email / Password' }}</span>
                            </div>
                            <div>
                                <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Tickets Submitted</label>
                                <span class="font-semibold text-slate-800">{{ $user->tickets->count() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div class="bg-white border border-rose-200 shadow-sm rounded-2xl p-6"
                         x-data="{ confirming: false }">
                        <h3 class="font-bold text-rose-700 text-base pb-3 border-b border-rose-100 mb-4">Danger Zone</h3>

                        @if ($user->id === auth()->id())
                            <p class="text-xs text-slate-500 text-center py-2">You cannot delete your own account.</p>
                        @else
                            <div x-show="!confirming">
                                <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                    Permanently delete this user account. This action cannot be undone and will remove all associated data.
                                </p>
                                <button @click="confirming = true"
                                        class="w-full inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold border border-rose-200 text-rose-600 hover:bg-rose-50 transition-all">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete User
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
                                    <button wire:click="deleteUser" wire:loading.attr="disabled"
                                            class="flex-1 inline-flex items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold bg-rose-600 hover:bg-rose-500 text-white transition-all">
                                        <span wire:loading.remove wire:target="deleteUser">Confirm Delete</span>
                                        <span wire:loading wire:target="deleteUser" class="flex items-center gap-1">
                                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Deleting...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
