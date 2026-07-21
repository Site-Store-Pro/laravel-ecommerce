<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users') }}" wire:navigate
               class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-900 leading-tight">Create New User</h2>
                <p class="text-sm text-slate-500 mt-0.5">Add a new account and assign their role</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl p-8">
                <form wire:submit="save" class="space-y-6">

                    {{-- Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                        <x-text-input wire:model="form.name" id="name" type="text"
                                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                      placeholder="Jane Smith" autofocus />
                        <x-input-error :messages="$errors->get('form.name')" class="mt-1.5 text-xs" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                        <x-text-input wire:model="form.email" id="email" type="email"
                                      class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                      placeholder="jane@example.com" />
                        <x-input-error :messages="$errors->get('form.email')" class="mt-1.5 text-xs" />
                    </div>

                    {{-- Role --}}
                    <div>
                        <x-input-label for="role" :value="__('Role')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                        <div class="mt-2 space-y-2">
                            @foreach ($roles as $role)
                                <label class="flex items-start gap-3 p-3.5 rounded-xl border cursor-pointer transition-all
                                    {{ $role->value === ($form->role_id ?? 1) ? 'border-indigo-300 bg-indigo-50/60' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                                    <input wire:model.live="form.role_id" type="radio" name="form.role_id" value="{{ $role->value }}"
                                           class="mt-0.5 text-indigo-600 border-slate-300 focus:ring-indigo-500" />
                                    <div>
                                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold {{ $role->value === 3 ? 'text-violet-700' : ($role->value === 2 ? 'text-indigo-700' : 'text-slate-700') }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $role->dotClasses() }}"></span>
                                            {{ $role->label() }}
                                        </span>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $role->description() }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('form.role_id')" class="mt-1.5 text-xs" />
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Password</p>

                        {{-- Password --}}
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="password" :value="__('Password')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                <x-text-input wire:model="form.password" id="password" type="password"
                                              class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                              placeholder="Min. 8 characters" />
                                <x-input-error :messages="$errors->get('form.password')" class="mt-1.5 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-500 text-xs font-semibold uppercase tracking-wider" />
                                <x-text-input wire:model="form.password_confirmation" id="password_confirmation" type="password"
                                              class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                              placeholder="Repeat password" />
                                <x-input-error :messages="$errors->get('form.password_confirmation')" class="mt-1.5 text-xs" />
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.users') }}" wire:navigate
                           class="text-sm text-slate-500 hover:text-slate-700 font-medium transition-colors">
                            Cancel
                        </a>
                        <x-primary-button wire:loading.attr="disabled"
                                          class="rounded-xl px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500">
                            <span wire:loading.remove wire:target="save">Create User</span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating...
                            </span>
                        </x-primary-button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
