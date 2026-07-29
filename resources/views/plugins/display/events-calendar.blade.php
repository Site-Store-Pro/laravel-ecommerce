@if(!empty($customCss))
    <style>
        {!! $customCss !!}
    </style>
@endif

<div id="{{ $instanceId }}" 
     x-data="eventsCalendarApp({
         events: {{ $eventsJson }},
         defaultLayout: '{{ $layout }}'
     })"
     class="w-full my-6 font-sans text-slate-800 dark:text-slate-100">

    <!-- Plugin Header & Toolbar Controls -->
    <div class="mb-6 p-4 sm:p-6 bg-slate-50/80 dark:bg-slate-800/80 rounded-3xl border border-slate-200/80 dark:border-slate-700/60 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            @if(!empty($header))
                <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $header }}</span>
                </h3>
            @endif
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                <span x-text="events.length"></span> upcoming event product<span x-text="events.length === 1 ? '' : 's'"></span> available for booking.
            </p>
        </div>

        <!-- Toolbar: Month Navigation & Layout Switcher -->
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-between md:justify-end">
            <!-- Month Nav (visible in Month View) -->
            <div x-show="layout === 'month'" class="flex items-center gap-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-1 shadow-2xs">
                <button type="button" @click="prevMonth" title="Previous Month" class="p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                
                <span class="text-xs font-extrabold text-slate-800 dark:text-slate-100 min-w-[110px] text-center uppercase tracking-wider px-2" x-text="monthYearName"></span>
                
                <button type="button" @click="nextMonth" title="Next Month" class="p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button type="button" @click="today" class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition">
                    @label('plugin.today', 'Today')
                </button>
            </div>

            <!-- View Switcher Tabs -->
            <div class="flex items-center gap-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl p-1 shadow-2xs">
                <button type="button" 
                        @click="layout = 'month'"
                        :class="layout === 'month' ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-indigo-600 font-medium'"
                        class="px-3 py-1.5 text-xs rounded-xl transition duration-150 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>@label('plugin.month_view', 'Month')</span>
                </button>
                <button type="button" 
                        @click="layout = 'list'"
                        :class="layout === 'list' ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-indigo-600 font-medium'"
                        class="px-3 py-1.5 text-xs rounded-xl transition duration-150 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>@label('plugin.agenda_view', 'Agenda')</span>
                </button>
                <button type="button" 
                        @click="layout = 'grid'"
                        :class="layout === 'grid' ? 'bg-indigo-600 text-white font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-indigo-600 font-medium'"
                        class="px-3 py-1.5 text-xs rounded-xl transition duration-150 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>@label('plugin.cards_view', 'Cards')</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 1. MONTH CALENDAR GRID VIEW -->
    <div x-show="layout === 'month'" x-transition class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-3xl overflow-hidden shadow-sm">
        <!-- Day of Week Headers -->
        <div class="grid grid-cols-7 border-b border-slate-200 dark:border-slate-700/80 bg-slate-50/70 dark:bg-slate-900/40 text-center font-extrabold text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400">
            <template x-for="dayName in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="dayName">
                <div class="py-3 px-1 border-r last:border-r-0 border-slate-200/60 dark:border-slate-700/60" x-text="dayName"></div>
            </template>
        </div>

        <!-- Calendar Day Cells Grid -->
        <div class="grid grid-cols-7 divide-x divide-y divide-slate-200/60 dark:divide-slate-700/60">
            <template x-for="(cell, idx) in calendarGrid" :key="idx">
                <div class="min-h-[110px] p-1.5 sm:p-2 transition duration-150 flex flex-col justify-between"
                     :class="{
                         'bg-slate-50/40 dark:bg-slate-900/30 text-slate-300 dark:text-slate-600': !cell.inMonth,
                         'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100': cell.inMonth,
                         'bg-indigo-50/30 dark:bg-indigo-950/20 ring-1 ring-indigo-500/20': cell.isToday
                     }">
                    
                    <!-- Date Number & Today Indicator -->
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold px-1.5 py-0.5 rounded-lg"
                              :class="{
                                  'bg-indigo-600 text-white shadow-xs font-black': cell.isToday,
                                  'text-slate-700 dark:text-slate-300': cell.inMonth && !cell.isToday,
                                  'text-slate-400 dark:text-slate-500 font-normal': !cell.inMonth
                              }"
                              x-text="cell.day"></span>

                        <template x-if="cell.events.length > 0">
                            <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded-full bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300"
                                  x-text="cell.events.length + ' event' + (cell.events.length > 1 ? 's' : '')"></span>
                        </template>
                    </div>

                    <!-- Events list inside day cell -->
                    <div class="space-y-1 overflow-y-auto max-h-[85px] pr-0.5">
                        <template x-for="evt in cell.events" :key="evt.id">
                            <div @click="openEventModal(evt)"
                                 class="p-1.5 rounded-xl cursor-pointer hover:scale-[1.02] transition duration-150 shadow-xs border text-[10px] leading-tight flex items-start gap-1 group"
                                 :style="'background-color: ' + evt.label_background + '15; border-color: ' + evt.label_background + '40;'">
                                
                                <span class="w-1.5 h-1.5 rounded-full shrink-0 mt-0.5" :style="'background-color: ' + evt.label_background"></span>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="font-extrabold truncate text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400" x-text="evt.title"></div>
                                    <div class="flex items-center justify-between text-[9px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        <span x-text="evt.start_time_fmt"></span>
                                        <span class="font-bold text-slate-700 dark:text-slate-200" x-text="evt.price"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- 2. AGENDA LIST VIEW -->
    <div x-show="layout === 'list'" x-transition class="space-y-3">
        <template x-for="evt in events" :key="evt.id">
            <div class="p-4 sm:p-5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-3xl shadow-sm hover:shadow-md transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                
                <!-- Event Image & Date Badge -->
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <img :src="evt.image" :alt="evt.title" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-slate-200/80 dark:border-slate-700/80 shrink-0">
                    
                    <div class="min-w-0 space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded-full text-white"
                                  :style="'background-color: ' + evt.label_background"
                                  x-text="evt.event_label"></span>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400" x-text="evt.date_range_fmt"></span>
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">&bull;</span>
                            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400" x-text="evt.start_time_fmt + (evt.end_time_fmt ? ' - ' + evt.end_time_fmt : '')"></span>
                        </div>

                        <h4 @click="openEventModal(evt)" class="text-base sm:text-lg font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition truncate" x-text="evt.title"></h4>

                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span x-text="evt.event_location"></span>
                        </p>
                    </div>
                </div>

                <!-- Price & CTA Button -->
                <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100 dark:border-slate-700/60">
                    <div class="text-left sm:text-right">
                        <span class="text-xs text-slate-400 uppercase font-bold block">@label('plugin.ticket_price', 'Ticket Price')</span>
                        <span class="text-lg font-extrabold text-slate-900 dark:text-white" x-text="evt.price"></span>
                    </div>

                    <a :href="evt.url" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-2xl shadow-md hover:scale-[1.02] transition flex items-center gap-1.5">
                        <span>@label('plugin.book_event', 'Book Event')</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- 3. CARDS GRID VIEW -->
    <div x-show="layout === 'grid'" x-transition class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="evt in events" :key="evt.id">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col group">
                
                <!-- Card Image & Badge -->
                <div class="relative h-44 overflow-hidden bg-slate-100 dark:bg-slate-900">
                    <img :src="evt.image" :alt="evt.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    
                    <span class="absolute top-3 left-3 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-full text-white shadow-md"
                          :style="'background-color: ' + evt.label_background"
                          x-text="evt.event_label"></span>

                    <span class="absolute bottom-3 right-3 px-3 py-1 text-xs font-black bg-slate-900/80 backdrop-blur-md text-white rounded-full shadow-md"
                          x-text="evt.price"></span>
                </div>

                <!-- Card Content -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span x-text="evt.date_range_fmt"></span>
                            <span>&bull;</span>
                            <span x-text="evt.start_time_fmt"></span>
                        </div>

                        <h4 @click="openEventModal(evt)" class="text-base font-extrabold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer transition line-clamp-2" x-text="evt.title"></h4>

                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2" x-text="evt.description"></p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-2">
                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 truncate flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span x-text="evt.event_location"></span>
                        </span>

                        <a :href="evt.url" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1 shrink-0">
                            <span>View</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- 4. INTERACTIVE EVENT DETAIL MODAL POPUP -->
    <template x-teleport="body">
        <div x-cloak 
             x-show="showModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.self="showModal = false"
             class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
            
            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="w-full max-w-lg bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-2xl overflow-hidden relative">
                
                <!-- Close Button -->
                <button type="button" @click="showModal = false" class="absolute top-4 right-4 z-10 p-2 rounded-full bg-slate-900/60 text-white hover:bg-slate-900 transition focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <template x-if="selectedEvent">
                    <div>
                        <!-- Modal Image Header -->
                        <div class="relative h-48 bg-slate-900 overflow-hidden">
                            <img :src="selectedEvent.image" :alt="selectedEvent.title" class="w-full h-full object-cover">
                            
                            <span class="absolute top-4 left-4 px-3 py-1 text-xs font-extrabold uppercase tracking-wider rounded-full text-white shadow-md"
                                  :style="'background-color: ' + selectedEvent.label_background"
                                  x-text="selectedEvent.event_label"></span>

                            <span class="absolute bottom-4 right-4 px-3.5 py-1.5 text-sm font-black bg-slate-900/80 backdrop-blur-md text-white rounded-full shadow-md"
                                  x-text="selectedEvent.price"></span>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-4">
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white" x-text="selectedEvent.title"></h3>
                                
                                <div class="mt-2 space-y-1 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span x-text="selectedEvent.date_range_fmt + ' @ ' + selectedEvent.start_time_fmt + (selectedEvent.end_time_fmt ? ' - ' + selectedEvent.end_time_fmt : '')"></span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        <span x-text="selectedEvent.event_location"></span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-900/50 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-700/60" x-text="selectedEvent.description"></p>

                            <div class="pt-2 flex items-center justify-end gap-3">
                                <button type="button" @click="showModal = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">
                                    @label('plugin.close', 'Close')
                                </button>
                                <a :href="selectedEvent.url" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-2xl shadow-md hover:scale-[1.02] transition flex items-center gap-1.5">
                                    <span>Book Event Ticket</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('eventsCalendarApp', (config) => ({
        events: config.events || [],
        layout: config.defaultLayout || 'month',
        year: new Date().getFullYear(),
        month: new Date().getMonth(), // 0 - 11
        selectedEvent: null,
        showModal: false,

        init() {
            // Auto-align calendar month to earliest event if available
            if (this.events.length > 0 && this.events[0].start_date_ymd) {
                const parts = this.events[0].start_date_ymd.split('-');
                if (parts.length === 3) {
                    this.year = parseInt(parts[0], 10);
                    this.month = parseInt(parts[1], 10) - 1;
                }
            }
        },

        get monthYearName() {
            const date = new Date(this.year, this.month, 1);
            return date.toLocaleString('default', { month: 'long', year: 'numeric' });
        },

        prevMonth() {
            if (this.month === 0) {
                this.month = 11;
                this.year--;
            } else {
                this.month--;
            }
        },

        nextMonth() {
            if (this.month === 11) {
                this.month = 0;
                this.year++;
            } else {
                this.month++;
            }
        },

        today() {
            const now = new Date();
            this.year = now.getFullYear();
            this.month = now.getMonth();
        },

        get calendarGrid() {
            const grid = [];
            const firstDayIndex = new Date(this.year, this.month, 1).getDay();
            const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
            const daysInPrevMonth = new Date(this.year, this.month, 0).getDate();

            const todayYmd = new Date().toISOString().split('T')[0];

            // Prev month padding
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                const prevM = this.month === 0 ? 11 : this.month - 1;
                const prevY = this.month === 0 ? this.year - 1 : this.year;
                const ymd = `${prevY}-${String(prevM + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
                
                grid.push({
                    day: dayNum,
                    inMonth: false,
                    isToday: ymd === todayYmd,
                    dateYmd: ymd,
                    events: this.getEventsForDate(ymd)
                });
            }

            // Current month days
            for (let d = 1; d <= daysInMonth; d++) {
                const ymd = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                grid.push({
                    day: d,
                    inMonth: true,
                    isToday: ymd === todayYmd,
                    dateYmd: ymd,
                    events: this.getEventsForDate(ymd)
                });
            }

            // Next month padding (total cells to 35 or 42)
            const remaining = 7 - (grid.length % 7);
            if (remaining < 7) {
                for (let d = 1; d <= remaining; d++) {
                    const nextM = this.month === 11 ? 0 : this.month + 1;
                    const nextY = this.month === 11 ? this.year + 1 : this.year;
                    const ymd = `${nextY}-${String(nextM + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                    
                    grid.push({
                        day: d,
                        inMonth: false,
                        isToday: ymd === todayYmd,
                        dateYmd: ymd,
                        events: this.getEventsForDate(ymd)
                    });
                }
            }

            return grid;
        },

        getEventsForDate(ymd) {
            return this.events.filter(e => {
                if (e.start_date_ymd === ymd) return true;
                if (e.end_date_ymd && e.start_date_ymd <= ymd && ymd <= e.end_date_ymd) return true;
                return false;
            });
        },

        openEventModal(evt) {
            this.selectedEvent = evt;
            this.showModal = true;
        }
    }));
});
</script>
