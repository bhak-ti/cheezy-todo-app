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
            @forelse ($todos as $date => $group)
                {{-- Header tanggal --}}
                <li class="list-group-item bg-gradient text-white fw-semibold d-flex align-items-center"
                    style="background: linear-gradient(90deg, #6c757d, #495057); border-left: 5px solid #ffc107; font-size: 1.1rem;">
                    <div class="d-flex align-items-center flex-grow-1">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ $date }}
                    </div>
                    <span style="font-size: 1rem; margin-right: 4.5rem;">
                        Status
                    </span>
                    <span style="font-size: 1rem; margin-right: 3.5rem;">
                        Aksi
                    </span>
                </li>

                {{-- List todo per group --}}
                @foreach ($group as $index => $todo)
                    <li class="list-group-item d-flex justify-content-between align-items-center todo-row
                        {{ $index % 2 === 0 ? 'striped' : '' }}"
                        style="background-color: #23272b; color: #f8f9fa; border: 1px solid #343a40;">

                        {{-- Keterangan todo --}}
                        <div style="margin-left: 0.9rem;">
                            <strong
                                @if($todo->TODOISDONE)
                                    style="text-decoration: line-through; color: #aaa;"
                                @endif
                            >
                                {{ $todo->TODODESC }}
                            </strong>

                            <div class="small" style="color: #ccc;">
                                Deadline: {{ $todo->formatted_deadline }}
                            </div>

                            <div class="small" style="color: #ccc;">
                                Selesai: {{ $todo->formatted_finish }}
                            </div>

                            <div class="small" style="color: #ccc;">
                                Dibuat: {{ $todo->formatted_created }}
                            </div>
                        </div>

                        {{-- Status & Checkbox --}}
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge {{ $todo->TODOISDONE ? 'bg-success' : 'bg-danger' }} px-3 py-2"
                                style="font-size: 1rem; letter-spacing: 0.5px; border-radius: 0.7rem;">
                                {{ $todo->TODOISDONE ? 'Selesai' : 'Belum' }}
                            </span>

                            <form action="{{ route('todos.toggle', $todo->TODOID) }}" method="POST" onsubmit="return confirm('Tandai todo ini sebagai {{ $todo->TODOISDONE ? 'belum selesai' : 'selesai' }}?')" class="mb-0">
                                @csrf
                                @method('PATCH')
                                <input type="checkbox" onChange="this.form.submit()" {{ $todo->TODOISDONE ? 'checked' : '' }}
                                    class="form-check-input mt-0"
                                    style="transform: scale(1.25); width: 1.3em; height: 1.3em; border: 2px solid rgba(255,255,255,0.5); border-radius: 6px; background-color: #23272b; cursor: pointer;">
                                </form>
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-warning d-flex align-items-center gap-2 px-3 py-2"
                                    data-bs-toggle="modal" data-bs-target="#editTodoModal{{ $todo->TODOID }}"
                                    style="font-size: 0.97rem; font-weight: 500; border-radius: 0.7rem;">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>

                                <!-- Modal Edit (harus di dalam loop) -->
                                <div class="modal fade" id="editTodoModal{{ $todo->TODOID }}" tabindex="-1" aria-labelledby="editTodoModalLabel{{ $todo->TODOID }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('todos.update', $todo->TODOID) }}" method="POST" class="modal-content bg-dark text-light border-secondary">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-secondary">
                                                <h5 class="modal-title" id="editTodoModalLabel{{ $todo->TODOID }}">Edit Todo</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="TODODESC{{ $todo->TODOID }}" class="form-label">Deskripsi</label>
                                                    <input type="text" class="form-control" name="TODODESC" id="TODODESC{{ $todo->TODOID }}" value="{{ $todo->TODODESC }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="TODODEADLINEDATE{{ $todo->TODOID }}" class="form-label">Deadline</label>
                                                    <input id="TODODEADLINEDATE{{ $todo->TODOID }}" name="TODODEADLINEDATE"
                                                    type="text" class="form-control deadline-datepicker"
                                                    value="{{ $todo->flatpickr_deadline }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-secondary">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </li>
                    @endforeach
                    @empty
                    <li class="list-group-item text-center text-muted">Belum ada todo.</li>
                    @endforelse
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
document.addEventListener('DOMContentLoaded', function () {
    fetch('https://api-harilibur.vercel.app/api')
        .then(response => response.json())
        .then(holidays => {
            const holidayDates = holidays.map(h => parseDateWithoutTimezone(h.holiday_date));

            // 1. Flatpickr untuk form create (gunakan ID spesifik)
            flatpickr("#TODODEADLINEDATE", {
                ...getFlatpickrOptions(holidayDates, holidays),
                defaultDate: null, // Input tetap kosong
                onOpen: function(selectedDates, dateStr, instance) {
                    if (!instance.input.value) {
                        instance.setDate(new Date(), false); // Set waktu sekarang di picker, tapi tidak mengisi input
                    }
                    setTimeout(initTooltips, 0);
                },
            });

            // 2. Flatpickr untuk form edit (gunakan class)
            document.querySelectorAll('.deadline-datepicker').forEach(function (el) {
                flatpickr(el, getFlatpickrOptions(holidayDates, holidays));
            });

            setTimeout(initTooltips, 0);
        })
        .catch(() => {
            // Fallback jika API gagal
            flatpickr("#TODODEADLINEDATE", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                defaultDate: null,
                onOpen: function(selectedDates, dateStr, instance) {
                    if (!instance.input.value) {
                        instance.setDate(new Date(), false);
                    }
                },
            });
            document.querySelectorAll('.deadline-datepicker').forEach(function (el) {
                flatpickr(el, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                });
            });
        });

    function parseDateWithoutTimezone(dateStr) {
        const parts = dateStr.split('-');
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function getFlatpickrOptions(holidayDates, holidays) {
        return {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const date = dayElem.dateObj;
                const isSunday = date.getDay() === 0;

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
            onMonthChange: () => setTimeout(initTooltips, 0),
            onYearChange: () => setTimeout(initTooltips, 0),
            onOpen: () => setTimeout(initTooltips, 0),
        };
    }

    function initTooltips() {
        document.querySelectorAll('.holiday-date[data-bs-toggle="tooltip"], .today-date[data-bs-toggle="tooltip"]').forEach(el => {
            if (el._tooltip) {
                el._tooltip.dispose();
                el._tooltip = null;
            }
        });
        document.querySelectorAll('.holiday-date[data-bs-toggle="tooltip"], .today-date[data-bs-toggle="tooltip"]').forEach(el => {
            el._tooltip = new bootstrap.Tooltip(el, {
                container: 'body',
                boundary: 'window',
                fallbackPlacements: ['top', 'bottom', 'right', 'left'],
            });
        });
    }
});

// Style untuk hari ini
document.head.insertAdjacentHTML('beforeend', `
<style>
    .flatpickr-day.today-date {
        border-radius: 50% !important;
        border: 2px solid #0d6efd !important;
        background: transparent !important;
        color: #0d6efd !important;
        font-weight: bold !important;
    }
</style>
`);
</script>
@endpush

@endsection
