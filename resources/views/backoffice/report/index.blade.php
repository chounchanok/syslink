<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        @include('backoffice.head')
        <style>
            @media print {
                body * { visibility: hidden; }
                #printable-area, #printable-area * { visibility: visible; }
                #printable-area { position: absolute; left: 0; top: 0; width: 100%; }
                .no-print { display: none !important; }
            }
        </style>
    </head>
    <?php $page="reports" ?>
    <body class="py-5">
        @include('backoffice.menu.navbar-modile')
        <div class="flex">
            @include('backoffice.menu.navbar')
            <div class="content">
                <div class="top-bar no-print">
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Application</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8 no-print">
                    <h2 class="text-lg font-medium mr-auto">
                        Operational Reports
                    </h2>
                    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                        <button class="btn btn-primary shadow-md mr-2" onclick="window.print()">
                            <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Print / Export PDF
                        </button>
                    </div>
                </div>

                <div class="intro-y box p-5 mt-5 no-print">
                    <form action="{{ route('reports') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label class="form-label text-xs">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $filters['start_date'] }}">
                        </div>
                        <div>
                            <label class="form-label text-xs">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $filters['end_date'] }}">
                        </div>
                        <div>
                            <label class="form-label text-xs">Project</label>
                            <select name="project_id" class="form-select w-48">
                                <option value="">All Projects</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}" {{ $filters['project_id'] == $p->id ? 'selected' : '' }}>
                                        {{ $p->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary"> <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter </button>
                        <a href="{{ route('reports') }}" class="btn btn-outline-secondary"> Reset </a>
                    </form>
                </div>

                <div id="printable-area">
                    <div class="hidden print:block mb-8 text-center mt-10">
                        <h1 class="text-2xl font-bold">Project Operation Report</h1>
                        <p class="text-slate-500">Period: {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}</p>
                    </div>

                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="clipboard" class="report-box__icon text-primary"></i>
                                        <div class="ml-auto">
                                            <div class="report-box__indicator bg-success tooltip cursor-pointer" title="Total Tasks"> All <i data-lucide="chevron-up" class="w-4 h-4 ml-0.5"></i> </div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">{{ $summary['total'] }}</div>
                                    <div class="text-base text-slate-500 mt-1">Total Tasks</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="check-square" class="report-box__icon text-success"></i>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">{{ $summary['completed'] }}</div>
                                    <div class="text-base text-slate-500 mt-1">Completed</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="clock" class="report-box__icon text-warning"></i>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">{{ $summary['pending'] }}</div>
                                    <div class="text-base text-slate-500 mt-1">Pending / In Progress</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="pie-chart" class="report-box__icon text-danger"></i>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">{{ $summary['progress'] }}%</div>
                                    <div class="text-base text-slate-500 mt-1">Success Rate</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-8">
                        <h3 class="font-bold text-lg mb-3">Task Details</h3>
                        <table class="table table-report -mt-2 table-bordered border-collapse border border-slate-200">
                            <thead>
                                <tr class="bg-slate-100">
                                    <th class="whitespace-nowrap border border-slate-200">DATE</th>
                                    <th class="whitespace-nowrap border border-slate-200">PROJECT / SITE</th>
                                    <th class="whitespace-nowrap border border-slate-200">TASK NAME</th>
                                    <th class="whitespace-nowrap text-center border border-slate-200">STATUS</th>
                                    <th class="whitespace-nowrap border border-slate-200">ASSIGNEE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                <tr class="intro-x">
                                    <td class="border border-slate-200">
                                        {{ \Carbon\Carbon::parse($task->start)->format('d/m/Y') }}
                                        <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($task->start)->format('H:i') }}</div>
                                    </td>
                                    <td class="border border-slate-200">
                                        <div class="font-medium">{{ $task->project->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $task->site->name ?? '-' }}</div>
                                    </td>
                                    <td class="border border-slate-200">
                                        {{ $task->title }}
                                    </td>
                                    <td class="text-center border border-slate-200">
                                        @if($task->status == 'completed')
                                            <span class="text-success font-bold">Completed</span>
                                        @elseif($task->status == 'waiting_approval')
                                            <span class="text-warning font-bold">Waiting</span>
                                        @else
                                            <span class="text-slate-500">{{ ucfirst($task->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="border border-slate-200">
                                        {{ $task->assignee_id ?? 'Unassigned' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4 text-xs text-slate-400 text-right print:block hidden">
                            Generated on {{ date('d/m/Y H:i') }} by {{ auth()->guard('admin')->user()->name ?? 'System' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('backoffice.js.js')
    </body>
</html>
