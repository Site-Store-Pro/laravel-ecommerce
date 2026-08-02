<div
    x-data="{
        autoScroll: true,
        lineClass(line) {
            if (line.includes('Processed:') || line.includes(' DONE')) return 'text-emerald-400';
            if (line.includes('Failed:')    || line.includes(' FAIL')) return 'text-red-400';
            if (line.includes('Processing:'))                           return 'text-amber-300';
            if (line.includes('==='))                                   return 'text-slate-500 italic';
            if (line.includes('[ERROR]'))                               return 'text-red-500 font-semibold';
            if (line.includes('INFO'))                                  return 'text-sky-400';
            return 'text-slate-300';
        },
        scrollBottom() {
            if (!this.autoScroll) return;
            this.$nextTick(() => {
                const el = this.$refs.logBox;
                if (el) el.scrollTop = el.scrollHeight;
            });
        }
    }"
    x-init="scrollBottom()"
    class="min-h-screen bg-gray-50 dark:bg-gray-900"
>

    {{-- ── Page Header ────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Queue Monitor
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Start and monitor the background translation job worker.
                </p>
            </div>

            {{-- Status badge + controls --}}
            <div class="flex items-center gap-3 flex-wrap">

                {{-- Running status badge --}}
                @if($isRunning)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 ring-1 ring-emerald-300 dark:ring-emerald-600">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Worker Running
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 ring-1 ring-gray-300 dark:ring-gray-600">
                    <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                    Worker Stopped
                </span>
                @endif

                {{-- Start button --}}
                @if(!$isRunning)
                <button wire:click="startWorker"
                        wire:loading.attr="disabled"
                        wire:target="startWorker"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors disabled:opacity-60">
                    <span wire:loading wire:target="startWorker" class="animate-spin w-4 h-4 border-2 border-white/40 border-t-white rounded-full"></span>
                    <svg class="w-4 h-4" wire:loading.remove wire:target="startWorker" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Start Worker
                </button>
                @endif

                {{-- Stop button --}}
                @if($isRunning)
                <button wire:click="stopWorker"
                        wire:confirm="Stop the queue worker? Any job currently in progress will finish first."
                        wire:loading.attr="disabled"
                        wire:target="stopWorker"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                    </svg>
                    Stop Worker
                </button>
                @endif

                {{-- Clear log --}}
                <button wire:click="clearLog"
                        wire:confirm="Clear the log? This cannot be undone."
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear Log
                </button>

            </div>
        </div>
    </div>

    {{-- ── Flash Message ───────────────────────────────────────────────────── --}}
    @if($flashMessage)
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => { show = false; $wire.clearFlash(); }, 5000)"
         class="mx-6 mt-4 flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-medium
                {{ $flashType === 'success'
                    ? 'bg-green-50 text-green-800 border border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700'
                    : 'bg-red-50 text-red-800 border border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-700' }}">
        @if($flashType === 'success')
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        @else
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        @endif
        {{ $flashMessage }}
    </div>
    @endif

    {{-- ── Stats Row ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 px-6 py-5">

        {{-- Pending --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pending</span>
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($this->pendingJobs) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">jobs in queue</p>
        </div>

        {{-- Processed this run --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Processed</span>
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($this->processedCount) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">in current log</p>
        </div>

        {{-- Failed this run --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Failed (log)</span>
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold {{ $this->failedCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ number_format($this->failedCount) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">in current log</p>
        </div>

        {{-- Failed jobs total in DB --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Failed (DB)</span>
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="text-2xl font-bold {{ $this->failedJobs > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ number_format($this->failedJobs) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">total in database</p>
        </div>

    </div>

    {{-- ── Worker Settings (only when stopped) ───────────────────────────── --}}
    @if(!$isRunning)
    <div class="px-6 pb-4 space-y-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Worker Settings</h2>
            <div class="flex flex-wrap gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Max Jobs Per Run</label>
                    <select wire:model="maxJobs"
                            class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="100">100 jobs</option>
                        <option value="250">250 jobs</option>
                        <option value="500">500 jobs</option>
                        <option value="1000">1,000 jobs</option>
                        <option value="2000">2,000 jobs</option>
                        <option value="5000">5,000 jobs</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Queue Name</label>
                    <input wire:model="queueName"
                           type="text"
                           placeholder="default"
                           class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-36">
                </div>
            </div>
        </div>

        {{-- Translation variant tip --}}
        @if($this->pendingJobs > 0)
        <div class="flex gap-3 rounded-xl border border-amber-200 dark:border-amber-700/50 bg-amber-50 dark:bg-amber-900/20 px-4 py-3">
            <svg class="w-4 h-4 text-amber-500 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                <span class="font-semibold">Tip — large catalogues with variant translations:</span>
                If your products have many variants with multiple options (e.g. Color × Size combinations), translation jobs are dispatched <em>per variant</em>, so the total job count can grow quickly.
                For stores with hundreds of variants, setting <strong>Max Jobs Per Run to 5,000</strong> is recommended to ensure a full translation batch completes in a single worker run without needing to be restarted.
            </p>
        </div>
        @endif
    </div>
    @endif

    {{-- ── Live Log Terminal ───────────────────────────────────────────────── --}}
    <div class="px-6 pb-6">
        <div class="rounded-xl border border-gray-700 dark:border-gray-700 overflow-hidden shadow-xl"
             style="background: #0d1117;">

            {{-- Terminal titlebar --}}
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-700"
                 style="background: #161b22;">
                <div class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full bg-red-500/80"></span>
                    <span class="h-3 w-3 rounded-full bg-amber-400/80"></span>
                    <span class="h-3 w-3 rounded-full bg-emerald-500/80"></span>
                </div>
                <span class="text-xs font-mono text-gray-500">
                    php artisan queue:work --queue={{ $queueName }} --stop-when-empty --max-jobs={{ $maxJobs }}
                </span>
                <div class="flex items-center gap-2">
                    @if($isRunning)
                    <span class="flex items-center gap-1 text-xs text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        LIVE
                    </span>
                    @else
                    <span class="text-xs text-gray-600">IDLE</span>
                    @endif
                    {{-- Auto-scroll toggle --}}
                    <button @click="autoScroll = !autoScroll"
                            :class="autoScroll ? 'text-indigo-400' : 'text-gray-600'"
                            title="Toggle auto-scroll"
                            class="text-xs font-mono hover:text-indigo-300 transition-colors">
                        ↓ auto
                    </button>
                </div>
            </div>

            {{-- Log output --}}
            <div x-ref="logBox"
                 x-on:livewire-update.window="scrollBottom()"
                 class="overflow-y-auto font-mono text-xs leading-relaxed p-4 space-y-0.5"
                 style="height: 480px; background: #0d1117;">

                @if(empty($this->logLines))
                <p class="text-gray-600 italic">No log output yet. Start the worker to begin processing jobs.</p>
                @else
                @foreach($this->logLines as $line)
                <div :class="lineClass({{ json_encode($line) }})"
                     class="whitespace-pre-wrap break-all">{{ $line }}</div>
                @endforeach
                @endif

            </div>
        </div>

        <p class="text-xs text-gray-400 dark:text-gray-600 mt-2">
            Log file: <code class="font-mono">storage/app/queue_worker.log</code>
            &nbsp;·&nbsp; PID file: <code class="font-mono">storage/app/queue_worker.pid</code>
        </p>
    </div>

    {{-- ── Polling heartbeat (3s while running, 15s while idle) ─────────────── --}}
    @if($isRunning)
        <div wire:poll.3000ms="refreshStatus" class="sr-only" aria-hidden="true"></div>
    @else
        <div wire:poll.15000ms="refreshStatus" class="sr-only" aria-hidden="true"></div>
    @endif

</div>
