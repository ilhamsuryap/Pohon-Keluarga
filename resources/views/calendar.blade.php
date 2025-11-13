@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kalender</h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                <div class="flex items-center space-x-2">
                    <button id="prevBtn" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 border">&lt;</button>
                    <div class="text-lg font-semibold" id="monthYear"></div>
                    <button id="nextBtn" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 border">&gt;</button>
                </div>

                <div class="flex items-center space-x-2">
                    <label for="yearSelect" class="text-sm">Tahun</label>
                    <select id="yearSelect" class="border rounded px-2 py-1 bg-white">
                        @for ($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                            <option value="{{ $y }}" @if ($y == $year) selected @endif>
                                {{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-fixed border-collapse">
                    <thead>
                        <tr class="text-sm text-gray-600 border-b">
                            <th class="w-1/7 p-3 text-left">Sen</th>
                            <th class="w-1/7 p-3 text-left">Sel</th>
                            <th class="w-1/7 p-3 text-left">Rab</th>
                            <th class="w-1/7 p-3 text-left">Kam</th>
                            <th class="w-1/7 p-3 text-left">Jum</th>
                            <th class="w-1/7 p-3 text-left">Sab</th>
                            <th class="w-1/7 p-3 text-left">Min</th>
                        </tr>
                    </thead>
                    <tbody id="calendarBody">
                        <!-- rendered by JS -->
                    </tbody>
                </table>
            </div>

            <div id="holidaysList" class="mt-6">
                <!-- Holidays summary rendered by JS -->
            </div>
        </div>
    </div>

    <script>
        (function() {
            let month = parseInt(@json($month)); // 1-12
            let year = parseInt(@json($year));

            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];

            const monthYearEl = document.getElementById('monthYear');
            const calendarBody = document.getElementById('calendarBody');
            const holidaysList = document.getElementById('holidaysList');

            document.getElementById('prevBtn').addEventListener('click', () => {
                month--;
                if (month < 1) {
                    month = 12;
                    year--;
                    updateYearSelect();
                }
                render();
            });
            document.getElementById('nextBtn').addEventListener('click', () => {
                month++;
                if (month > 12) {
                    month = 1;
                    year++;
                    updateYearSelect();
                }
                render();
            });
            document.getElementById('yearSelect').addEventListener('change', (e) => {
                year = parseInt(e.target.value);
                render();
            });

            function updateYearSelect() {
                document.getElementById('yearSelect').value = year;
            }

            async function fetchHolidays(month, year) {
                const url = `https://api-harilibur.vercel.app/api?month=${month}&year=${year}`;
                try {
                    const res = await fetch(url);
                    return await res.json();
                } catch (e) {
                    console.error('Failed to fetch holidays', e);
                    return null;
                }
            }

            function toISO(y, m, d) {
                return `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            }

            function normalizeHolidays(raw) {
                if (!raw) return [];

                // If API returns array directly
                if (Array.isArray(raw)) return raw;
                // some shapes: { data: [...] } or {holidays: [...]}
                if (raw.data && Array.isArray(raw.data)) return raw.data;
                if (raw.holidays && Array.isArray(raw.holidays)) return raw.holidays;

                // In many cases this API returns an object where keys are indexes or dates
                const arr = [];
                for (const k in raw) {
                    if (!Object.prototype.hasOwnProperty.call(raw, k)) continue;
                    const item = raw[k];
                    if (item && typeof item === 'object') {
                        arr.push(item);
                    }
                }
                return arr;
            }

            function mapHoliday(h) {
                // Normalize common shape used in screenshot: {holiday_date, holiday_name, is_national_holiday}
                const date = h.holiday_date || h.date || h.tanggal || h.start || null;
                const name = h.holiday_name || h.name || h.nama || h.title || h.event || h.description || '';
                const national = h.is_national_holiday === true || h.is_national_holiday === 'true' || h.national ===
                    true || false;
                if (!date) return null;
                return {
                    date: (date.split ? date.split('T')[0] : date),
                    name: name || 'Libur',
                    national
                };
            }

            async function render() {
                monthYearEl.textContent = `${monthNames[month-1]} ${year}`;
                calendarBody.innerHTML =
                    '<tr><td colspan="7" class="p-6 text-sm text-gray-500">Memuat kalender…</td></tr>';
                holidaysList.innerHTML = '';

                const raw = await fetchHolidays(month, year);
                const normalized = normalizeHolidays(raw).map(mapHoliday).filter(Boolean);

                // build map keyed by ISO date
                const holidayMap = {};
                normalized.forEach(h => {
                    holidayMap[h.date] = holidayMap[h.date] || [];
                    holidayMap[h.date].push(h);
                });

                // calendar matrix (Monday-first)
                const firstOfMonth = new Date(year, month - 1, 1);
                const lastOfMonth = new Date(year, month, 0);
                const daysInMonth = lastOfMonth.getDate();
                const firstWeekday = (firstOfMonth.getDay() + 6) % 7; // 0=Mon

                const weeks = [];
                let cur = 1 - firstWeekday;
                while (cur <= daysInMonth) {
                    const week = [];
                    for (let i = 0; i < 7; i++) {
                        week.push(cur < 1 || cur > daysInMonth ? null : cur);
                        cur++;
                    }
                    weeks.push(week);
                }

                // render weeks
                calendarBody.innerHTML = '';
                weeks.forEach(week => {
                    const tr = document.createElement('tr');
                    week.forEach(day => {
                        const td = document.createElement('td');
                        td.className = 'align-top border-t p-3 h-28 align-top relative';
                        if (day === null) {
                            td.innerHTML = '';
                        } else {
                            const iso = toISO(year, month, day);

                            // day number top-right
                            const dayNum = document.createElement('div');
                            dayNum.className = 'absolute top-2 right-3 text-sm text-gray-600';
                            dayNum.textContent = day;
                            td.appendChild(dayNum);

                            // holidays for the day
                            const hs = holidayMap[iso] || [];
                            if (hs.length) {
                                const container = document.createElement('div');
                                container.className = 'mt-6 flex flex-col gap-1';
                                hs.forEach(h => {
                                    const b = document.createElement('div');
                                    b.className = 'text-xs rounded px-2 py-0.5 truncate';
                                    b.title = h.name + (h.national ? ' (Nasional)' : '');
                                    if (h.national) {
                                        b.classList.add('bg-red-100', 'text-red-800');
                                    } else {
                                        b.classList.add('bg-amber-100', 'text-amber-800');
                                    }
                                    b.textContent = h.name;
                                    container.appendChild(b);
                                });
                                td.appendChild(container);
                            }
                        }
                        tr.appendChild(td);
                    });
                    calendarBody.appendChild(tr);
                });

                // holidays summary
                const holidayDates = Object.keys(holidayMap).sort();
                if (holidayDates.length) {
                    const wrap = document.createElement('div');
                    wrap.innerHTML = '<h3 class="font-medium mb-2">Hari Libur / Acara</h3>';
                    const ul = document.createElement('ul');
                    ul.className = 'list-disc pl-5 space-y-1 text-sm';
                    holidayDates.forEach(d => {
                        holidayMap[d].forEach(h => {
                            const li = document.createElement('li');
                            li.innerHTML =
                                `<strong class="mr-2">${d}</strong> ${escapeHtml(h.name)}${h.national ? ' <span class="ml-2 text-xs text-red-600">(Nasional)</span>' : ''}`;
                            ul.appendChild(li);
                        });
                    });
                    wrap.appendChild(ul);
                    holidaysList.appendChild(wrap);
                } else {
                    holidaysList.innerHTML =
                        '<div class="text-sm text-gray-500">Tidak ada hari libur terdaftar untuk bulan ini.</div>';
                }
            }

            function escapeHtml(s) {
                return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            updateYearSelect();
            render();
        })();
    </script>
@endsection
