<div>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">
                    {{ __('Users') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Manage accounts, roles, and team members</p>
            </div>
            <a href="{{ route('admin.users.create') }}" wire:navigate
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-sm shadow-indigo-100 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add User
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-toast-alert />

            {{-- Stats Row --}}
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-3 lg:grid-cols-5">
                <div class="bg-white border border-slate-200/70 p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-slate-500/10 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Users</p>
                    <h3 class="text-2xl font-extrabold text-slate-700 mt-2">{{ array_sum($roleCounts) }}</h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-indigo-500/10 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.5C18 2.01 15.99 0 13.5 0 12.1 0 10.85.66 10 1.68L9 2.8 7.99 1.67C7.14.66 5.9 0 4.5 0 2.01 0 0 2.01 0 4.5c0 .62.11 1.06.18 1.5H2C.9 6 0 6.9 0 8v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM13.5 2c1.38 0 2.5 1.12 2.5 2.5S14.88 7 13.5 7 11 5.88 11 4.5 12.12 2 13.5 2zM4.5 2C5.88 2 7 3.12 7 4.5S5.88 7 4.5 7 2 5.88 2 4.5 3.12 2 4.5 2zM20 20H2V8h7.08L7 10.22V12h10V10.22L14.92 8H20v12z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Wholesale</p>
                    <h3 class="text-2xl font-extrabold text-indigo-600 mt-2">{{ $roleCounts['wholesale'] ?? 0 }}</h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-emerald-500/10 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Order Processors</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-2">{{ $roleCounts['order_processor'] ?? 0 }}</h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-cyan-500/10 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h.01M9 13h.01M9 17h.01"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ticket Managers</p>
                    <h3 class="text-2xl font-extrabold text-cyan-600 mt-2">{{ $roleCounts['ticket_manager'] ?? 0 }}</h3>
                </div>

                <div class="bg-white border border-slate-200/70 p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-violet-500/10 group-hover:scale-110 transition-transform pointer-events-none">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Admins</p>
                    <h3 class="text-2xl font-extrabold text-violet-600 mt-2">{{ $roleCounts['admin'] ?? 0 }}</h3>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm p-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label for="search" :value="__('Search Users')" class="text-slate-500 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <x-text-input wire:model.live.debounce.300ms="search" id="search"
                                          class="block w-full ps-9 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                          type="search" placeholder="Search by name or email..." />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="roleFilter" :value="__('Role Filter')" class="text-slate-500 font-semibold" />
                        <select wire:model.live="roleFilter" id="roleFilter"
                                class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}">{{ $role->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Users Table --}}
            <div class="bg-white border border-slate-200/70 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Tickets</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Orders</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Joined</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Verified</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-9 w-9 rounded-xl flex items-center justify-center font-bold text-sm shrink-0
                                                {{ $user->role_id === \App\Enums\UserRole::Admin ? 'bg-violet-100 text-violet-700' : ($user->role_id === \App\Enums\UserRole::Wholesale ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700') }}">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-slate-800 text-sm">{{ $user->name }}</span>
                                                <span class="text-xs text-slate-400">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold border {{ $user->role_id->badgeClasses() }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $user->role_id->dotClasses() }}"></span>
                                            {{ $user->role_id->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $user->tickets_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $user->orders_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $user->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $isGuestUser = $user->password === \App\Models\User::GUEST_PASSWORD; @endphp
                                        <div class="flex flex-col gap-0.5">
                                            @if ($isGuestUser)
                                                <div class="flex flex-col gap-1">
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 border border-orange-200 w-fit">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                        Guest
                                                    </span>
                                                    <span class="text-xs text-amber-500 font-semibold">Unverified</span>
                                                </div>
                                            @elseif ($user->email_verified_at)
                                                <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-semibold">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Verified
                                                </span>
                                            @else
                                                <span class="text-xs text-amber-500 font-semibold">Unverified</span>
                                            @endif

                                            @if (!empty($user->provider))
                                                <span class="text-[11px] text-slate-500 font-medium capitalize">{{ $user->provider }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" wire:navigate
                                           class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all">
                                            View
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" wire:navigate
                                           class="inline-flex items-center gap-1.5 justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="p-3 rounded-2xl bg-slate-50 text-slate-400 mb-4 border border-slate-100">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                            <h4 class="font-bold text-slate-800 text-base">No users found</h4>
                                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Try adjusting your search or role filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
