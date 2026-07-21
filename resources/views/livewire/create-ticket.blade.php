<div>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-900 leading-tight">
            {{ __('Submit Support Ticket') }}
        </h2>
        <p class="text-sm text-slate-500 mt-1">Describe your issue and we'll get back to you as soon as possible.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200/70 shadow-sm rounded-2xl overflow-hidden">
                <form wire:submit="save" class="space-y-6 p-6 sm:p-8">
                    <!-- Subject/Title -->
                    <div>
                        <x-input-label for="title" :value="__('Subject / Ticket Title')" class="text-slate-700 font-semibold" />
                        <x-text-input wire:model="title" id="title" class="block mt-1.5 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm" type="text" required placeholder="e.g. Can't access billing portal or load invoices" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2 text-xs" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Detailed Description')" class="text-slate-700 font-semibold" />
                        <textarea wire:model="description" id="description" rows="6"
                                  class="mt-1.5 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                  required placeholder="Describe what went wrong, what steps you were taking, and any error messages you saw."></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2 text-xs" />
                    </div>

                    <!-- Attachments -->
                    <div>
                        <x-input-label for="attachments" :value="__('Attachments (optional)')" class="text-slate-700 font-semibold" />
                        <div class="mt-1.5 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-xl hover:border-slate-300 transition-colors bg-slate-50/50 relative">
                            <div class="space-y-1 text-center pointer-events-none">
                                <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <span class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        Upload files
                                    </span>
                                    <p class="ps-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-400">PNG, JPG, GIF, PDF, TXT or DOC up to {{ config('support.max_attachment_size_kb') }}KB each</p>
                            </div>
                            <input wire:model="attachments" id="attachments" type="file" multiple
                                   accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.txt,.doc,.docx"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        </div>
                        
                        <!-- Temporary upload list -->
                        @if (!empty($attachments))
                            <div class="mt-4 p-3 rounded-xl border border-slate-100 bg-slate-50/30 space-y-2">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Files Selected (Ready to Upload)</p>
                                <ul class="divide-y divide-slate-100 text-sm text-slate-700">
                                    @foreach ($attachments as $attachment)
                                        <li class="py-2 flex items-center justify-between">
                                            <span class="truncate font-medium">{{ $attachment->getClientOriginalName() }}</span>
                                            <span class="text-xs text-slate-400 whitespace-nowrap">{{ round($attachment->getSize() / 1024, 1) }} KB</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <x-input-error :messages="$errors->get('attachments.*')" class="mt-2 text-xs" />
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('dashboard') }}" wire:navigate 
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-all">
                            Cancel
                        </a>
                        <x-primary-button wire:loading.attr="disabled" class="rounded-xl px-5 py-2.5">
                            <span wire:loading.remove wire:target="save" class="flex items-center">
                                Submit Ticket
                                <svg class="w-4 h-4 ms-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
