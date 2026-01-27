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
                            <li class="breadcrumb-item"><a href="#">Application</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Project Management</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <h2 class="intro-y text-lg font-medium mt-10">
                    Project Management
                </h2>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                        <form action="{{route('project.create')}}" method="POST" class="flex flex-wrap gap-2 items-end w-full lg:w-auto">
                            @csrf
                            <div class="flex flex-col">
                                <label class="text-xs text-slate-500">Project Code</label>
                                <input class="form-control w-32" type="text" name="code" placeholder="PO-XXXX" required>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs text-slate-500">Project Name</label>
                                <input class="form-control w-48" type="text" name="name" placeholder="Project Name" required>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs text-slate-500">Start Date</label>
                                <input class="form-control w-36" type="date" name="start_date" required>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs text-slate-500">End Date</label>
                                <input class="form-control w-36" type="date" name="end_date" required>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs text-slate-500">Color</label>
                                <input type="color" name="color" class="h-10 w-12 p-1 rounded cursor-pointer" value="#3b82f6">
                            </div>
                            <button class="btn btn-primary shadow-md">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Project
                            </button>
                        </form>

                        <div class="hidden md:block mx-auto text-slate-400"></div>

                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-slate-500">
                                <form action="{{route('project')}}" method="GET">
                                    <input type="text" class="form-control w-56 box pr-10" name="search" value="{{ $search ?? '' }}" placeholder="Search Code/Name...">
                                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3">
                                        <i class="w-4 h-4" data-lucide="search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">CODE</th>
                                    <th class="whitespace-nowrap">PROJECT NAME</th>
                                    <th class="whitespace-nowrap text-center">SITES</th>
                                    <th class="whitespace-nowrap">STATUS</th>
                                    <th class="whitespace-nowrap">DURATION</th>
                                    <th class="whitespace-nowrap">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($project as $item)
                                <tr class="intro-x">
                                    <td>
                                        <div class="font-medium whitespace-nowrap">{{ $item->code }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium whitespace-nowrap">{{ $item->name }}</div>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Updated: {{ $item->updated_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="text-center">
                                        @php $site_count = \App\Models\Site::where('project_id', $item->id)->count(); @endphp
                                        <span class="px-2 py-1 rounded-full bg-slate-100 text-xs">{{ $site_count }} Sites</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center whitespace-nowrap">
                                            @if($item->status == 'completed')
                                                <div class="flex items-center text-success"> <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> Completed </div>
                                            @elseif($item->status == 'active')
                                                <div class="flex items-center text-primary"> <i data-lucide="activity" class="w-4 h-4 mr-2"></i> Active </div>
                                            @elseif($item->status == 'hold')
                                                <div class="flex items-center text-danger"> <i data-lucide="pause-circle" class="w-4 h-4 mr-2"></i> On Hold </div>
                                            @else
                                                <div class="flex items-center text-slate-500"> <i data-lucide="clock" class="w-4 h-4 mr-2"></i> Planned </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($item->start_date)->format('d M y') }} -
                                            {{ \Carbon\Carbon::parse($item->end_date)->format('d M y') }}
                                        </div>
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3 text-primary" href="{{ route('project.sites', $item->id) }}">
                                                <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i> Sites
                                            </a>
                                            <a class="flex items-center mr-3" href="{{ route('project.edit', $item->id) }}">
                                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                                            </a>
                                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview{{$item->id}}">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Del
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <div id="delete-modal-preview{{$item->id}}" class="modal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <form action="{{ route('project.delete') }}" method="post">
                                                    @csrf
                                                    <div class="p-5 text-center">
                                                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                                        <div class="text-3xl mt-5">Are you sure?</div>
                                                        <div class="text-slate-500 mt-2">Delete Project: {{ $item->code }}?<br>All Sites & Tasks will be removed.</div>
                                                    </div>
                                                    <input type="hidden" name="id" value="{{$item->id}}">
                                                    <div class="px-5 pb-8 text-center">
                                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                                        <button type="submit" class="btn btn-danger w-24">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                        <nav class="w-full sm:w-auto sm:mr-auto">
                            <ul class="pagination">
                                {{ $project->appends(['search' => $search])->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('backoffice.js.js')

        @if (!empty(session('success')))
        <div id="success-notification-toggle" class="toastify-content hidden flex">
            <i class="text-success" data-lucide="check-circle"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Success!</div>
                <div class="text-slate-500 mt-1">Operation completed successfully.</div>
            </div>
        </div>
        <script>
            window.onload = function() {
                Toastify({
                    node: $("#success-notification-toggle").clone().removeClass("hidden")[0],
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                }).showToast();
            };
        </script>
        @endif
    </body>
</html>
