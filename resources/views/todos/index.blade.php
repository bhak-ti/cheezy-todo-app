@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Todo</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTodoModal">
            + Tambah Todo
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($todos->isEmpty())
        <div class="alert alert-info">Belum ada todo. Yuk buat satu!</div>
    @else
        <ul class="list-group bg-dark rounded-3 shadow-sm">
            @foreach ($todos as $todo)
                <li class="list-group-item d-flex justify-content-between align-items-center"
                    style="background-color: #23272b; color: #f8f9fa; border: 1px solid #343a40;">
                    
                    {{-- Detail todo --}}
                    <div>
                        <strong>{{ $todo->TODODESC }}</strong>

                        <div class="small" style="color: #ccc;">
                            Deadline: {{ \Carbon\Carbon::parse($todo->TODODEADLINEDATE)->format('d M Y H:i') }}
                        </div>

                        <div class="small" style="color: #ccc;">
                            Finish: {{ $todo->TODOFINISHTIMESTAMP ? \Carbon\Carbon::parse($todo->TODOFINISHTIMESTAMP)->format('d M Y H:i') : '-' }}
                        </div>

                        <div class="small" style="color: #ccc;">
                            Dibuat: {{ \Carbon\Carbon::parse($todo->created_at)->format('d M Y H:i') }}
                        </div>
                    </div>

                    {{-- Status & Checkbox --}}
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge px-2 py-1 {{ $todo->TODOISDONE ? 'bg-success' : 'bg-danger' }}"
                            style="font-size: 1rem;">
                            {{ $todo->TODOISDONE ? 'Selesai' : 'Belum' }}
                        </span>

                        <input type="checkbox" disabled {{ $todo->TODOISDONE ? 'checked' : '' }}
                            class="form-check-input mt-0"
                            style="transform: scale(1.2); width: 1.2em; height: 1.2em; border: 2px solid rgba(255,255,255,0.4); border-radius: 4px; background-color: transparent;">
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- Modal Create Todo -->
    <div class="modal fade" id="createTodoModal" tabindex="-1" aria-labelledby="createTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('todos.store') }}" method="POST" class="modal-content bg-dark text-light border-secondary">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="createTodoModalLabel">Tambah Todo Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="TODODESC" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" name="TODODESC" id="TODODESC" required>
                    </div>
                    <div class="mb-3">
                        <label for="TODODEADLINEDATE" class="form-label">Deadline (Opsional)</label>
                        <input id="TODODEADLINEDATE" name="TODODEADLINEDATE" type="text" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('https://api-harilibur.vercel.app/api')
        .then(response => response.json())
        .then(holidays => {
            const holidayDates = holidays.map(h => parseDateWithoutTimezone(h.holiday_date));

            flatpickr("#TODODEADLINEDATE", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const date = dayElem.dateObj;
                    const isSunday = date.getDay() === 0;

                    // Penanda hari ini
                    const today = new Date();
                    if (
                        date.getFullYear() === today.getFullYear() &&
                        date.getMonth() === today.getMonth() &&
                        date.getDate() === today.getDate()
                    ) {
                        dayElem.classList.add('today-date');
                        dayElem.setAttribute('data-bs-toggle', 'tooltip');
                        dayElem.setAttribute('title', 'Hari Ini');
                    }

                    // Cari apakah tanggal ini adalah hari libur nasional
                    const holiday = holidays.find(h => {
                        const holDate = parseDateWithoutTimezone(h.holiday_date);
                        return holDate.getFullYear() === date.getFullYear() &&
                            holDate.getMonth() === date.getMonth() &&
                            holDate.getDate() === date.getDate();
                    });

                    if (isSunday || holiday) {
                        dayElem.classList.add('holiday-date');
                        dayElem.setAttribute('data-bs-toggle', 'tooltip');
                        if (holiday) {
                            dayElem.setAttribute('title', holiday.holiday_name);
                        } else if (isSunday) {
                            dayElem.setAttribute('title', 'Hari Minggu');
                        }
                    }
                },
                onMonthChange: function() {
                    setTimeout(initTooltips, 0);
                },
                onYearChange: function() {
                    setTimeout(initTooltips, 0);
                },
                onOpen: function() {
                    setTimeout(initTooltips, 0);
                }
            });

            function initTooltips() {
                // Hapus tooltip lama
                document.querySelectorAll('.holiday-date[data-bs-toggle="tooltip"], .today-date[data-bs-toggle="tooltip"]').forEach(el => {
                    if (el._tooltip) {
                        el._tooltip.dispose();
                        el._tooltip = null;
                    }
                });
                // Inisialisasi tooltip baru
                document.querySelectorAll('.holiday-date[data-bs-toggle="tooltip"], .today-date[data-bs-toggle="tooltip"]').forEach(el => {
                    el._tooltip = new bootstrap.Tooltip(el, {
                        container: 'body',
                        boundary: 'window',
                        fallbackPlacements: ['top', 'bottom', 'right', 'left'],
                    });
                });
            }

            setTimeout(initTooltips, 0);
        })
        .catch(() => {
            flatpickr("#TODODEADLINEDATE", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
            });
        });

    function parseDateWithoutTimezone(dateStr) {
        const parts = dateStr.split('-');
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }
});

// Tambahkan style untuk penanda hari ini
document.head.insertAdjacentHTML('beforeend', `
<style>
    .flatpickr-day.today-date {
        border-radius: 50% !important;
        border: 2px solid #FFD966 !important;
        background: transparent !important;
        color: #FFD966 !important;
        font-weight: bold !important;
    }
</style>
`);
</script>

@endpush



@endsection
