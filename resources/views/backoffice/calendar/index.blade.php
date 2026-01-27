<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        @include('backoffice.head')

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <style>
            /* ปรับแต่ง Style ให้เข้ากับ Theme */
            .fc-event { cursor: pointer; }
            .fc-daygrid-event { font-size: 0.85rem; padding: 2px 4px; border-radius: 4px; }
            .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; color: #475569; }
            .fc-button-primary { background-color: #1e40af !important; border-color: #1e40af !important; }

            /* พื้นที่สำหรับลากงาน */
            #external-events .fc-event {
                margin: 10px 0;
                padding: 10px;
                background: #f1f5f9;
                border: 1px solid #e2e8f0;
                color: #475569;
                cursor: move;
                border-radius: 0.375rem;
            }
        </style>
    </head>
    <?php $page="calendar" ?>
    <body class="py-5">
        @include('backoffice.menu.navbar-modile')
        <div class="flex">
            @include('backoffice.menu.navbar')
            <div class="content">
                <div class="top-bar">
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Application</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Task Management</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>
                <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Calendar & Task Management
                    </h2>
                    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                        <button class="btn btn-primary shadow-md mr-2" id="btn-add-task">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add New Task
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-5 mt-5">
                    <div class="col-span-12 xl:col-span-3 lg:col-span-4">
                        <div class="box p-5 intro-y">
                            <div class="relative flex items-center p-5">
                                <div class="w-12 h-12 flex-none image-fit mr-1">
                                    <div class="rounded-full bg-slate-100 flex items-center justify-center w-full h-full font-bold text-slate-500">JP</div>
                                </div>
                                <div class="ml-2 overflow-hidden">
                                    <div class="text-base font-medium truncate">Job Waiting List</div>
                                    <div class="text-slate-500 text-xs">Drag and drop to calendar</div>
                                </div>
                            </div>
                            <div class="border-t border-slate-200/60 p-5">
                                <div id='external-events'>
                                    <div class='fc-event' data-event='{"title":"Survey Site A", "color":"#3b82f6"}'>
                                        <i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i> Survey Site A
                                    </div>
                                    <div class='fc-event' data-event='{"title":"Install CCTV", "color":"#10b981"}'>
                                        <i data-lucide="camera" class="w-4 h-4 inline mr-1"></i> Install CCTV
                                    </div>
                                    <div class='fc-event' data-event='{"title":"Maintenance Server", "color":"#f59e0b"}'>
                                        <i data-lucide="server" class="w-4 h-4 inline mr-1"></i> Maintenance Server
                                    </div>
                                    <div class='fc-event' data-event='{"title":"Meeting Client", "color":"#ef4444"}'>
                                        <i data-lucide="users" class="w-4 h-4 inline mr-1"></i> Meeting Client
                                    </div>
                                </div>
                                <div class="form-check mt-5">
                                    <input class="form-check-input" type="checkbox" id="drop-remove">
                                    <label class="form-check-label" for="drop-remove">Remove after drop</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 xl:col-span-9 lg:col-span-8">
                        <div class="box p-5 intro-y">
                            <div id='calendar'></div>
                        </div>
                    </div>
                    </div>
            </div>
            </div>

        @include('backoffice.js.js')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var Calendar = FullCalendar.Calendar;
                var Draggable = FullCalendar.Draggable;

                var containerEl = document.getElementById('external-events');
                var calendarEl = document.getElementById('calendar');
                var checkbox = document.getElementById('drop-remove');

                // 1. Initialize Draggable Events (Side Menu)
                new Draggable(containerEl, {
                    itemSelector: '.fc-event',
                    eventData: function(eventEl) {
                        return JSON.parse(eventEl.getAttribute('data-event'));
                    }
                });

                // 2. Initialize Calendar
                var calendar = new Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    editable: true,
                    droppable: true, // อนุญาตให้ลากจากข้างนอกลงมาได้
                    dayMaxEvents: true,

                    // Events Source (ดึงจาก Laravel Controller)
                    events: "{{ route('calendar.events') }}",

                    // เมื่อมีการ Drop จากข้างนอก
                    drop: function(info) {
                        // เช็คว่าต้องการลบออกจาก list ซ้ายมือไหม
                        if (checkbox.checked) {
                            info.draggedEl.parentNode.removeChild(info.draggedEl);
                        }

                        saveTask({
                            title: info.draggedEl.innerText.trim(),
                            start: info.dateStr,
                            allDay: info.allDay
                        });
                    },

                    // เมื่อมีการเลื่อนงานในปฏิทิน (Update)
                    eventDrop: function(info) {
                        updateTask(info.event);
                    },

                    // เมื่อมีการย่อขยายเวลางาน
                    eventResize: function(info) {
                        updateTask(info.event);
                    }
                });

                calendar.render();

                // --- Helper Functions for AJAX ---

                function saveTask(data) {
                    console.log('Saving new task:', data);
                }

                function updateTask(event) {
                    // ยิง Ajax ไป update
                    const data = {
                        id: event.id,
                        start: event.startStr,
                        end: event.endStr,
                        _token: "{{ csrf_token() }}"
                    };

                    // เรียกใช้ jQuery หรือ Fetch ตามถนัด
                    fetch("{{ route('calendar.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Toast Notification (ใช้ของ Theme หรือ Swal)
                        // Swal.fire('Success', 'Updated!', 'success');
                        console.log('Updated');
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        </script>
        </body>
</html>
