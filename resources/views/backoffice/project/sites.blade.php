<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        @include('backoffice.head')
    </head>
    <?php $page="project" ?>
    <body class="py-5">
        @include('backoffice.menu.navbar-modile')
        <div class="flex">
            @include('backoffice.menu.navbar')
            <div class="content">
                <div class="top-bar">
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('project') }}">Project</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sites Management</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        {{-- แก้ไข: เช็คว่ามี project หรือไม่ --}}
                        @if($project)
                            Sites for Project: {{ $project->name }} ({{$project->code}})
                        @else
                            All Sites List
                        @endif
                    </h2>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                        {{-- แก้ไข: แสดงฟอร์มเฉพาะตอนที่มี Project เท่านั้น --}}
                        @if($project)
                        <form action="{{ route('project.site_create') }}" method="POST" class="flex flex-wrap gap-2 w-full">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <input class="form-control w-48" type="text" name="name" placeholder="Site Name (e.g. Building A)" required>
                            <input class="form-control w-64" type="text" name="address" placeholder="Address / Location">
                            <input class="form-control w-32" type="text" name="lat" placeholder="Latitude">
                            <input class="form-control w-32" type="text" name="lng" placeholder="Longitude">
                            <button class="btn btn-primary shadow-md">Add Site</button>
                        </form>
                        @else
                        {{-- ถ้าไม่มี Project ให้แสดงข้อความแจ้งเตือน --}}
                        <div class="alert alert-outline-warning flex items-center mb-2 w-full" role="alert">
                            <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
                            Please select a project from the "All Projects" menu to add a new site.
                        </div>
                        @endif
                    </div>

                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">#</th>
                                    {{-- เพิ่มคอลัมน์ Project กรณีดูรวม --}}
                                    @if(!$project) <th class="whitespace-nowrap">PROJECT</th> @endif
                                    <th class="whitespace-nowrap">SITE NAME</th>
                                    <th class="whitespace-nowrap">ADDRESS</th>
                                    <th class="whitespace-nowrap">GPS</th>
                                    <th class="whitespace-nowrap">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sites as $key => $site)
                                <tr class="intro-x">
                                    <td class="w-10">{{ $key+1 }}</td>

                                    {{-- แสดงชื่อ Project กรณีดูรวม --}}
                                    @if(!$project)
                                    <td>
                                        @if($site->project)
                                            <a href="{{ route('project.sites', $site->project->id) }}" class="font-medium text-primary">
                                                {{ $site->project->name }}
                                            </a>
                                            <div class="text-xs text-slate-500">{{ $site->project->code }}</div>
                                        @else
                                            <span class="text-danger">Unassigned</span>
                                        @endif
                                    </td>
                                    @endif

                                    <td>
                                        <div class="font-medium">{{ $site->name }}</div>
                                    </td>
                                    <td>
                                        <div class="text-slate-500">{{ $site->address ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @if($site->lat && $site->lng)
                                            <a href="https://maps.google.com/?q={{$site->lat}},{{$site->lng}}" target="_blank" class="text-primary underline">
                                                {{ number_format($site->lat, 4) }}, {{ number_format($site->lng, 4) }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">No GPS</span>
                                        @endif
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <form action="{{ route('project.site_delete') }}" method="POST" onsubmit="return confirm('Delete this site?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $site->id }}">
                                                <button class="flex items-center text-danger"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('backoffice.js.js')
        @if (!empty(session('success')))
        <script>
            // ใช้ SweetAlert หรือ Toastify ตาม Theme
            // alert('Success');
        </script>
        @endif
    </body>
</html>
