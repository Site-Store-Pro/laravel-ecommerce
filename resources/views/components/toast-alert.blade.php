<div wire:key="toast-alert-container"
     x-data="{ 
        show: false,
        type: 'success',
        message: '',
        timeout: null,
        trigger(type, message, duration) {
            this.show = true;
            this.type = type;
            this.message = message;
            if (this.timeout) clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.show = false, duration || 4000);
        }
     }" 
     x-init="
        @if(session()->has('status') || session()->has('success') || session()->has('error') || session()->has('warning'))
            trigger('{{ session()->has('error') ? 'error' : (session()->has('warning') ? 'warning' : 'success') }}', {{ Js::from(session('error') ?: session('status') ?: session('success') ?: session('warning')) }});
        @endif
      "
      @toast.window="trigger($event.detail.type || 'success', $event.detail.message || '', $event.detail.duration || 4000)"
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2 md:translate-y-0 md:translate-x-4"
     x-transition:enter-end="opacity-100 translate-y-0 md:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-6 right-6 z-[9999] max-w-sm w-full bg-white border border-slate-100/80 border-l-4 shadow-2xl rounded-2xl p-4 flex items-center justify-between gap-3 text-slate-800"
     :class="type === 'error' ? 'border-l-rose-600 shadow-rose-100/40' : (type === 'warning' ? 'border-l-amber-500 shadow-amber-100/40' : 'border-l-emerald-600 shadow-emerald-100/40')"
     role="alert"
     style="display: none;">
    <div class="flex items-center gap-3">
        <span class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
              :class="type === 'error' ? 'bg-rose-50 text-rose-600' : (type === 'warning' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600')">
            <template x-if="type === 'error'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </template>
            <template x-if="type === 'warning'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </template>
            <template x-if="type !== 'error' && type !== 'warning'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
        </span>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" x-text="type === 'error' ? 'Error' : (type === 'warning' ? 'Warning' : 'Success')"></p>
            <p class="text-slate-800 text-xs font-semibold mt-0.5" x-html="message"></p>
        </div>
    </div>
    <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
