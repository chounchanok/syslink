<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        @include('backoffice.head')
    </head>
    <?php $page="job" ?>
    <body class="py-5">
        @include('backoffice.menu.navbar-modile')
        <div class="flex">
            @include('backoffice.menu.navbar')
            <div class="content">
                <div class="top-bar">
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('job') }}">Job</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Work Logs</li>
                        </ol>
                    </nav>
                    @include('backoffice.menu.account_menu')
                </div>

                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Site Check-in Logs
                    </h2>
                </div>

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">USER / TECHNICIAN</th>
                                    <th class="whitespace-nowrap">TASK INFO</th>
                                    <th class="whitespace-nowrap">CHECK-IN</th>
                                    <th class="whitespace-nowrap">CHECK-OUT</th>
                                    <th class="whitespace-nowrap text-center">DURATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                <tr class="intro-x">
                                    <td>
                                        <div class="font-medium">{{ $log->user->name ?? 'Unknown User' }}</div>
                                        <div class="text-slate-500 text-xs mt-0.5">ID: {{ $log->user_id }}</div>
                                    </td>
                                    <td>
                                        <div class="font-medium">{{ $log->task->title ?? 'General Task' }}</div>
                                        @if($log->note)
                                            <div class="text-slate-500 text-xs italic">Note: {{ $log->note }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-medium text-success">
                                            {{ \Carbon\Carbon::parse($log->check_in_time)->format('d/m/Y H:i') }}
                                        </div>
                                        @if($log->check_in_lat && $log->check_in_lng)
                                            <a href="https://maps.google.com/?q={{ $log->check_in_lat }},{{ $log->check_in_lng }}" target="_blank" class="text-xs text-primary underline flex items-center">
                                                <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i> View Map
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">No GPS</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->check_out_time)
                                            <div class="font-medium text-danger">
                                                {{ \Carbon\Carbon::parse($log->check_out_time)->format('d/m/Y H:i') }}
                                            </div>
                                            @if($log->check_out_lat && $log->check_out_lng)
                                                <a href="https://maps.google.com/?q={{ $log->check_out_lat }},{{ $log->check_out_lng }}" target="_blank" class="text-xs text-primary underline flex items-center">
                                                    <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i> View Map
                                                </a>
                                            @endif
                                        @else
                                            <span class="py-1 px-2 rounded-full bg-success text-white text-xs">Working Now</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($log->check_out_time && $log->check_in_time)
                                            @php
                                                $in = \Carbon\Carbon::parse($log->check_in_time);
                                                $out = \Carbon\Carbon::parse($log->check_out_time);
                                                $diff = $in->diff($out);
                                            @endphp
                                            <div class="text-slate-500">{{ $diff->format('%H h %I m') }}</div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                        <nav class="w-full sm:w-auto sm:mr-auto">
                            <ul class="pagination">
                                {{ $logs->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('backoffice.js.js')
    </body>
</html>
