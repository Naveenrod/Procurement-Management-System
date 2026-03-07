<x-app-layout>
    <x-slot name="title">Calendar</x-slot>

    {{-- FullCalendar v6 --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <div class="py-6 space-y-4" x-data="calendarPage()">

        {{-- Header + Filters --}}
        <div class="bg-white rounded-lg shadow-sm border p-4 flex flex-wrap items-center gap-4">
            <h2 class="text-base font-semibold text-gray-800 mr-2">Schedule & Timeline</h2>

            <div class="flex flex-wrap items-center gap-2">
                {{-- Filter toggles --}}
                <button @click="toggleType('delivery')"
                        :class="activeTypes.includes('delivery') ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-colors">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> PO Deliveries
                </button>
                <button @click="toggleType('contract')"
                        :class="activeTypes.includes('contract') ? 'bg-purple-100 text-purple-700 border-purple-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-colors">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span> Contract Expiry
                </button>
                <button @click="toggleType('maintenance')"
                        :class="activeTypes.includes('maintenance') ? 'bg-amber-100 text-amber-700 border-amber-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-colors">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Maintenance
                </button>
                <button @click="toggleType('trip')"
                        :class="activeTypes.includes('trip') ? 'bg-emerald-100 text-emerald-700 border-emerald-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-colors">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Trips
                </button>
                <button @click="toggleType('shipment')"
                        :class="activeTypes.includes('shipment') ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-white text-gray-500 border-gray-200'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-colors">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Shipment ETAs
                </button>
            </div>

            <div class="ml-auto flex items-center gap-2">
                <button @click="setView('dayGridMonth')"
                        :class="currentView === 'dayGridMonth' ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                        class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors">Month</button>
                <button @click="setView('timeGridWeek')"
                        :class="currentView === 'timeGridWeek' ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                        class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors">Week</button>
                <button @click="setView('listMonth')"
                        :class="currentView === 'listMonth' ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                        class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors">List</button>
            </div>
        </div>

        {{-- Contract expiry legend note --}}
        <div class="flex items-center gap-4 text-xs text-gray-500 px-1">
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-purple-500 inline-block"></span> Contract &gt; 90 days</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-orange-500 inline-block"></span> Contract expiring in 90 days</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-red-500 inline-block"></span> Contract expiring in 30 days</span>
        </div>

        {{-- Calendar --}}
        <div class="bg-white rounded-lg shadow-sm border p-4">
            <div id="calendar"></div>
        </div>

        {{-- Event detail tooltip --}}
        <div x-show="tooltip.visible" x-cloak
             :style="'top:' + tooltip.y + 'px; left:' + tooltip.x + 'px'"
             class="fixed z-50 bg-gray-900 text-white text-xs rounded-lg shadow-xl p-3 max-w-xs pointer-events-none">
            <p class="font-semibold mb-1" x-text="tooltip.title"></p>
            <p class="text-gray-300" x-text="tooltip.badge"></p>
            <p class="text-gray-400 mt-0.5" x-text="tooltip.status"></p>
        </div>
    </div>

    <script>
    function calendarPage() {
        return {
            calendar: null,
            currentView: 'dayGridMonth',
            activeTypes: ['delivery', 'contract', 'maintenance', 'trip', 'shipment'],
            tooltip: { visible: false, title: '', badge: '', status: '', x: 0, y: 0 },

            init() {
                this.$nextTick(() => this.initCalendar());
            },

            initCalendar() {
                const self = this;
                const el = document.getElementById('calendar');
                this.calendar = new FullCalendar.Calendar(el, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left:   'prev,next',
                        center: 'title',
                        right:  'today'
                    },
                    height: 'auto',
                    eventSources: [{
                        url: '{{ route('calendar.events') }}',
                        method: 'GET',
                        extraParams: () => ({ types: self.activeTypes }),
                        failure: () => console.error('Failed to load events'),
                    }],
                    eventClick(info) {
                        if (info.event.url) {
                            info.jsEvent.preventDefault();
                            window.location.href = info.event.url;
                        }
                    },
                    eventMouseEnter(info) {
                        const rect = info.el.getBoundingClientRect();
                        const props = info.event.extendedProps;
                        self.tooltip = {
                            visible: true,
                            title:   info.event.title,
                            badge:   props.badge  ?? '',
                            status:  props.status ? 'Status: ' + props.status.replace(/_/g, ' ') : '',
                            x: Math.min(rect.left + window.scrollX, window.innerWidth - 260),
                            y: rect.bottom + window.scrollY + 6,
                        };
                    },
                    eventMouseLeave() {
                        self.tooltip.visible = false;
                    },
                    eventDidMount(info) {
                        info.el.style.cursor = info.event.url ? 'pointer' : 'default';
                    },
                    noEventsContent: 'No events for this period.',
                    loading(isLoading) {
                        // could add a spinner here
                    },
                });
                this.calendar.render();
            },

            toggleType(type) {
                if (this.activeTypes.includes(type)) {
                    this.activeTypes = this.activeTypes.filter(t => t !== type);
                } else {
                    this.activeTypes.push(type);
                }
                this.calendar.refetchEvents();
            },

            setView(view) {
                this.currentView = view;
                this.calendar.changeView(view);
            },
        };
    }
    </script>
</x-app-layout>
