<!DOCTYPE html>

<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>

        @include('backoffice.head')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
        @php
        session()->forget('previous_url');
            session()->put('previous_url', url()->current());
        @endphp

        <!-- END: CSS Assets-->
    </head>
    <?php
    $page="product"
    ?>
    <!-- END: Head -->
    <body class="py-5">
        <!-- BEGIN: Mobile Menu -->
        @include('backoffice.menu.navbar-modile')
        <!-- END: Mobile Menu -->
        <div class="flex">
            <!-- BEGIN: Side Menu -->
            @include('backoffice.menu.navbar')
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                <!-- BEGIN: Top Bar -->
                <div class="top-bar">
                    <!-- BEGIN: Breadcrumb -->
                    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Application</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                    <!-- END: Breadcrumb -->
                    @include('backoffice.menu.account_menu')
                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 2xl:col-span-12">
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">General Report</h2>
                    <a href="" class="ml-auto flex items-center text-primary">
                        <i data-lucide="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data
                    </a>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5" style="overflow: auto;height: 500px;">
                    @foreach ($project as $item)
                    <div class="col-span-12 sm:col-span-6 xl:col-span-2 intro-y">
                        <a href="{{ url("/?type=".$item->id."&search=".@$search."#myTable") }}">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    @php
                                    // ดึงจำนวนงานตามชื่อ project
                                    $count = $dataarr[$item->name] ?? 0;
                                    @endphp
                                    <div class="text-3xl font-medium leading-8 mt-6">{{ $count }}</div>
                                    {{-- <div class="text-3xl font-medium leading-8 mt-6">{{$dataarr[0]}}</div> --}}
                                    <div class="text-base text-slate-500 mt-1"  style="color:{{$item->color}}">{{$item->name}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach


                    <!-- <div class="col-span-12 sm:col-span-6 xl:col-span-2 intro-y">
                        <div class="report-box zoom-in">
                            <div class="box p-5">
                                {{-- <div class="flex">
                                    <i data-lucide="file-text" class="report-box__icon" style="color: rgba(152, 219, 246, 0.9);"></i>
                                </div> --}}
                                @php
                                    // ดึงค่าของ other จาก $dataarr
                                    $otherCount = $dataarr['other'] ?? 0;
                                @endphp
                                <div class="text-3xl font-medium leading-8 mt-6">{{$otherCount}}</div>
                                <div class="text-base text-slate-500 mt-1" style="color: rgba(152, 219, 246, 0.9);">อื่นๆ</div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <!-- END: General Report -->

            <!-- BEGIN: Weekly Top Seller -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">Type</h2>
                    {{-- <a href="" class="ml-auto text-primary truncate">Show More</a> --}}
                </div>
                <div class="intro-y box p-5 mt-5">
                    <div class="mt-3">
                        <div class="h-[213px]">
                            <canvas id="report-type-pie-chart"></canvas>
                        </div>
                    </div>
                    <div class="w-52 sm:w-auto mx-auto mt-8" style="overflow: auto;height: 130px;">
                        @foreach ($project as $item)
                        <input type="hidden" name="project_id" value="{{$item->id}}" id="">
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 rounded-full mr-3" style="background-color: {{$item->color}}"></div>
                                <button type="button" onclick="sendData('{{$item->id}}')">
                                    <span class="truncate">{{$item->name}}</span>
                                </button>
                                @php
                                    // หากใช้ชื่อ project เป็น key
                                    $count = $dataarr[$item->name] ?? 0;

                                    // หรือหากใช้ type_id เป็น key
                                    // $count = $dataarr[$item->id] ?? 0;
                                @endphp
                                <span class="font-medium ml-auto">{{$count}}</span>
                        </div>
                        @endforeach
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 rounded-full mr-3" style="background-color: rgba(152, 219, 246, 0.9)"></div>
                                <button type="button" onclick="sendData('other')">
                                    <span class="truncate">อื่นๆ</span>
                                </button>
                                @php
                                    // ดึงค่าของ other จาก $dataarr
                                    $otherCount = $dataarr['other'] ?? 0;
                                @endphp
                                <span class="font-medium ml-auto">{{$otherCount}}</span>
                        </div>
                        {{-- <div class="flex items-center mt-4">
                            <div class="w-2 h-2 rounded-full mr-3" style="background-color: rgba(142, 216, 117, 0.9)"></div>
                        <button type="button" onclick="sendData('prime')">
                            <span class="truncate">Prime</span>
                        </button>
                            <span class="font-medium ml-auto">{{$dataarr[1]}}</span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 rounded-full mr-3" style="background-color: rgba(241, 169, 131, 0.9)"></div>
                        <button type="button" onclick="sendData('service')">
                            <span class="truncate">Service</span>
                        </button>
                            <span class="font-medium ml-auto">{{$dataarr[2]}}</span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 rounded-full mr-3" style="background-color: rgba(1, 111, 196, 0.9)"></div>
                        <button type="button" onclick="sendData('วงจร')">
                            <span class="truncate">วงจร</span>
                        </button>
                            <span class="font-medium ml-auto">{{$dataarr[3]}}</span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 rounded-full mr-3" style="background-color: rgba(194, 82, 7, 0.9)"></div>
                        <button type="button" onclick="sendData('วงจร')">
                            <span class="truncate">cctv</span>
                        </button>
                            <span class="font-medium ml-auto">{{$dataarr[4]}}</span>
                        </div> --}}


                    </div>
                </div>
            </div>
            <!-- END: Weekly Top Seller -->
            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">Progress</h2>
                    {{-- <a href="" class="ml-auto text-primary truncate">Show More</a> --}}
                </div>
                <div class="intro-y box p-5 mt-5">
                    <div class="mt-3">
                        <div class="h-[213px]">
                            <canvas id="report-progress-donut-chart"></canvas>
                        </div>
                    </div>
                    <div class="w-52 sm:w-auto mx-auto mt-8">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="truncate">in progress</span>
                            <span class="font-medium ml-auto" id="in_progress">{{$dataarr_progress[0]}}</span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 bg-success rounded-full mr-3"></div>
                            <span class="truncate">success</span>
                            <span class="font-medium ml-auto" id="success">{{$dataarr_progress[1]}}</span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 bg-warning rounded-full mr-3"></div>
                            <span class="truncate">wait</span>
                            <span class="font-medium ml-auto" id="wait">{{$dataarr_progress[2]}}</span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-2 h-2 bg-danger rounded-full mr-3"></div>
                            <span class="truncate">cancel</span>
                            <span class="font-medium ml-auto" id="cancle">{{$dataarr_progress[3]}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Sales Report -->

            <!-- BEGIN: Weekly Top Products -->
            <div class="col-span-12 mt-6" id="search_table">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">Work Report</h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                        <div class="w-56 relative text-slate-500">
                            <form action="{{url('/')."#myTable"}}" method="GET" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="{{ request('type') }}">

                                <div class="relative w-56">
                                    <input type="text" class="form-control w-full box pr-10" name="search" id="search" value="{{ @$search }}" placeholder="Search...">
                                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3">
                                        <i class="w-4 h-4" data-lucide="search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#large-modal-size-preview" class="ml-3 btn box flex items-center text-slate-600 dark:text-slate-300">
                            <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Report Install
                        </a>
                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#large-modal-size-project" class="ml-3 btn box flex items-center text-slate-600 dark:text-slate-300">
                            <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Report Project
                        </a>
                        <div id="large-modal-size-preview" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="font-medium text-base mr-auto">Report Install</h2>
                                    </div>
                                    <form action="/export/excel/team_report" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                            <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-1" class="form-label">From</label> <input type="date" id="modal-datepicker-1" name="start_date" class="form-control" data-single-mode="true"> </div>
                                            <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-2" class="form-label">To</label> <input type="date" id="modal-datepicker-2" name="end_date" class="form-control" data-single-mode="true"> </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                                            <button type="submit" class="btn btn-primary w-20">export</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="large-modal-size-project" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="/export/excel/project_report" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                            <div class="col-span-12 sm:col-span-12"> <label for="modal-form-6" class="form-label">Project</label>
                                                <select name="project" class="form-control" id="job" required>
                                                    <option value="เลือก">--เลือกโปรเจค--</option>
                                                    @foreach ($project as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                    <option value="other">อื่นๆ</option>
                                                </select>
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <label for="modal-datepicker-1" class="form-label">From</label>
                                                <input type="date" id="modal-datepicker-1" name="start_date" class="form-control" data-single-mode="true" required>
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <label for="modal-datepicker-2" class="form-label">To</label>
                                                <input type="date" id="modal-datepicker-2" name="end_date" class="form-control" data-single-mode="true" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                                            <button type="submit" class="btn btn-primary w-20">export</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                    <table class="table table-report sm:mt-2" id="myTable">
                        <thead>
                            <tr>
                                {{-- <th class="whitespace-nowrap">IMAGES</th> --}}
                                <th class="whitespace-nowrap">WORK</th>
                                <th class="text-center whitespace-nowrap">NAME</th>
                                <th class="text-center whitespace-nowrap">CUSTOMER</th>
                                <th class="text-center whitespace-nowrap">DATE</th>
                                <th class="text-center whitespace-nowrap">TECHNICIAN</th>
                                <th class="text-center whitespace-nowrap">ENGINEER</th>
                                <th class="text-center whitespace-nowrap">STATUS</th>
                                {{-- <th class="text-center whitespace-nowrap">PROGRESS</th> --}}
                                <th class="text-center whitespace-nowrap">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $key => $item)
                                <tr class="intro-x">

                                    <td>
                                        @php
                                        $type = DB::table('tb_project')->where('id',$item->type)->first();
                                        // dd($type);
                                        @endphp
                                        {{$type != null ? $type->name : 'Other'}}
                                        {{-- <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">PC & Laptop</div> --}}
                                    </td>
                                    <td class="text-center">
                                        {{$item->job_name}}
                                    </td>
                                    <td class="text-center">{{$item->customer_name}}</td>
                                    <td class="text-center">
                                        @if ($item->status == 'ติดตั้ง')
                                        <span class="text-xs px-1 mr-1">{{ \Carbon\Carbon::parse($item->install_date)->format('Y-m-d H:i') }}</span>
                                    @elseif($item->status == 'สำรวจ')
                                        <span class="text-xs px-1 mr-1">{{ \Carbon\Carbon::parse($item->survay_date)->format('Y-m-d H:i') }}</span>
                                    @else
                                        <span class="text-xs px-1 mr-1">{{ \Carbon\Carbon::parse($item->service_date)->format('Y-m-d H:i') }}</span>
                                    @endif
                                    
                                    </td>
                                    @php
                                        $technician = DB::table('tb_team')->where('id',$item->technician)->first();
                                        $engineer = DB::table('users')->where('id',$item->engineer)->first();
                                    @endphp
                                    <td class="text-center">{{@$technician->team_name}}</td>
                                    <td class="text-center">{{@$engineer->name}}</td>
                                    <td class="w-40">
                                        <div class="flex items-center justify-center ">
                                            @if ($item->status_job == 'in progress')
                                                <span class="text-xs px-1 bg-primary text-white mr-1">
                                            @elseif ($item->status_job == 'success')
                                                <span class="text-xs px-1 bg-success text-white mr-1">
                                            @elseif ($item->status_job == 'wait')
                                                <span class="text-xs px-1 bg-warning text-white mr-1">
                                            @elseif ($item->status_job == 'cancle')
                                                <span class="text-xs px-1 bg-danger text-white mr-1">
                                            @endif
                                             {{$item->status}} </div>
                                    </td>
                                    {{-- <td class="w-40">
                                        <div class="flex items-center justify-center ">
                                            @if ($item->status_job == 'in progress')
                                                <span class="text-xs px-1 bg-primary text-white mr-1">
                                            @elseif ($item->status_job == 'success')
                                                <span class="text-xs px-1 bg-success text-white mr-1">
                                            @elseif ($item->status_job == 'wait')
                                                <span class="text-xs px-1 bg-warning text-white mr-1">
                                            @elseif ($item->status_job == 'cancle')
                                                <span class="text-xs px-1 bg-danger text-white mr-1">
                                            @endif
                                                {{$item->status_job}}
                                            </span>
                                        </div>
                                    </td> --}}
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="{{url('/job/submit_work')}}/{{$item->id}}">
                                                <i data-lucide="eye" class="block mx-auto"></i>
                                                view
                                            </a>
                                            @if (auth()->guard('admin')->user()->role == 'superadmin' || auth()->guard('admin')->user()->permission == 1)

                                            <a class="flex items-center mr-3" href="{{url("/job/edit/$item->id")}}">
                                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                                            </a>
                                            @endif
                                            @if (auth()->guard('admin')->user()->role == 'superadmin' )

                                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview{{$item->id}}" class="flex items-center mr-3" style="color: red">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete</a>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="intro-y flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-3">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            @if ($search == null)
                            {{ $report->links() }}

                            @else
                            {{ $report->appends(['search' => $search])->links() }}

                            @endif
                        </ul>
                    </nav>
                    <select class="w-20 form-select box mt-3 sm:mt-0" id="page" name="page" onchange="document.location.href=`{{url('/')}}?show=${this.value}`">
                        <option value="10" {{@$show=='10'?'selected':''}}>10</option>
                        <option value="25" {{@$show=='25'?'selected':''}}>25</option>
                        <option value="35" {{@$show=='35'?'selected':''}}>35</option>
                        <option value="50" {{@$show=='50'?'selected':''}}>50</option>
                    </select>
                </div>
                @foreach ($report as $item)
                <div id="delete-modal-preview{{$item->id}}" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <form action="{{url('/job/delete')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                <div class="p-5 text-center"> <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                    <div class="text-3xl mt-5">Are you sure?</div>
                                    <div class="text-slate-500 mt-2">Do you really want to delete these records? <br>This process cannot be undone.</div>
                                </div>
                                <input type="hidden" name="id" value="{{$item->id}}" id="">
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
            </div>

            <!-- END: Weekly Top Products -->
            <div class="text-center" style="display: none"> <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#button-modal-preview" class="btn btn-primary" id="auto-click-button">Show Modal</a> </div> <!-- END: Modal Toggle -->
                    <!-- BEGIN: Modal Content -->
                    <div id="button-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i> </a>
                                <div class="modal-body p-0">
                                    <div class="p-5 text-center"> <i data-lucide="check-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">Success</div>
                                        {{-- <div class="text-slate-500 mt-2">Modal with close button</div> --}}
                                    </div>
                                    <div class="px-5 pb-8 text-center"> <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-24">Ok</button> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (!empty(session('success')) and session('success') == 1)
                    <script>
                        // เมื่อหน้าเว็บโหลดเสร็จ ให้คลิกปุ่มโดยอัตโนมัติ
                        window.onload = function() {
                            document.getElementById('auto-click-button').click();
                        };
                    </script>
                    @endif
        </div>

    </div>

</div>
</div>
<!-- END: Content -->
</div>
<!-- BEGIN: Dark Mode Switcher-->

<!-- END: Dark Mode Switcher-->

<!-- BEGIN: JS Assets-->
{{-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
<script src="dist/js/app.js"></script> --}}


@include('backoffice.js.js')
<!-- END: JS Assets-->
{{-- <script>
    var arr01=[];
    var arr02=[];

</script>
@php
    $colors=json_decode($colors);
    foreach ($colors as $key => $value) {
        echo "
        <script>
            arr01.push('$value')
        </script>
    ";
    }
    $labels=json_decode($labels);
    foreach ($labels as $key => $item) {
        echo "
        <script>
            arr02.push('$item')
        </script>
    ";
    }
    // dd($colors);

@endphp
<script>
    // alert(JSON.stringify(arr02));
    var dataarr = JSON.parse('{{$dataarr_pie}}');
    var dataarr_progress = JSON.parse('{{json_encode($dataarr_progress)}}');
    var colors = arr01;
    var labels = arr02;

    jspie(dataarr,dataarr_progress,labels,colors);

    // console.log(dataarr);
</script> --}}
{{-- @php
    $colors = json_decode($colors);
    $labels = json_decode($labels);
@endphp --}}

<script>
    // ตัวแปรที่ได้จาก Controller ถูกส่งไปยัง JavaScript โดยตรง
    var dataarr = JSON.parse(@json($dataarr_pie)); // ค่าของ $dataarr_pie เป็น JSON string
    var labels = @json($labels);  // ส่งค่าจาก PHP มาให้ JavaScript
    var colors = @json($colors);  // ส่งค่าจาก PHP มาให้ JavaScript

    function sendData(value) {
        fetch('/get_jobtype', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                data: value
            }),
        })
        .then(response => response.json())
        .then(data => {
            var dataarr_progress = JSON.parse(data.dataarr_progress);

            setTimeout(() => {
                jspie(dataarr, dataarr_progress, labels, colors);
            }, 1500);

            document.getElementById('in_progress').innerText = dataarr_progress[0] ?? "0";
            document.getElementById('success').innerText = dataarr_progress[1] ?? "0";
            document.getElementById('wait').innerText = dataarr_progress[2] ?? "0";
            document.getElementById('cancle').innerText = dataarr_progress[3] ?? "0";
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

<script>
function batbal(id){

    Swal.fire({
    title:"Are you sure?",
    html:"Do you really want to delete these records?",
    icon:"error",
    showCancelButton:true,
    confirmButtonText:"Delete",
    confirmButtonColor:"#DD6B55",
    showLoaderOnConfirm: true,
    preConfirm: () => {
        return fetch('/backoffice/product/delete/'+id)
        .then(response => response.json())
        .then(data => location.reload())
        .catch(error => { Swal.showValidationMessage(`Request failed: ${error}`)})
    }
    });
    }
</script>
<script>
    function sendData(value) {
        // alert(value)
        // var projectId = document.getElementById('project_id').value
        // alert
        fetch('/get_jobtype', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            _token : "{{csrf_token()}}",
            data: value,

        }),
        })
        .then(response => response.json())
        .then(data => {
            // ส่วนนี้สามารถใช้เมื่อมีการตอบกลับจาก Controller โดยการประมวลผลต่อไป
            // console.log(data.dataarr_progress ); // ให้ดำเนินการต่อตามที่คุณต้องการ
    var dataarr_progress = JSON.parse(data.dataarr_progress);

            setTimeout(() => {
                jspie(dataarr,dataarr_progress,labels,colors);
                // alert(data.dataarr_progress[0]);
            }, 1500);

            document.getElementById('in_progress').innerText = dataarr_progress[0]==0?"0":dataarr_progress[0];
            document.getElementById('success').innerText = dataarr_progress[1]==0?"0":dataarr_progress[1];
            document.getElementById('wait').innerText = dataarr_progress[2]==0?"0":dataarr_progress[2];
            document.getElementById('cancle').innerText = dataarr_progress[3]==0?"0":dataarr_progress[3];


        })
        .catch(error => {
            console.error('Error:', error);
        });

}

</script>

<script>
    function myFunction() {
        var input, filter, table, tbody, tr, td, i, j, txtValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tbody = table.getElementsByTagName("tbody")[0]; // เลือก tbody เพื่อค้นหาข้อมูลเฉพาะ
        tr = tbody.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            var display = false;
            var tds = tr[i].getElementsByTagName("td");
            for (j = 0; j < tds.length; j++) {
                td = tds[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        display = true;
                        break;
                    }
                }
            }
            if (display) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

@if (session('error'))
<script>
    Swal.fire({
icon: 'error',
title: 'Oops...',
html: '{{session('error')}}',

})
</script>
@endif
@if(isset($searched) && $searched)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const element = document.getElementById("search_table");
        if(element){
            element.scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>
@endif
</body>
</html>
