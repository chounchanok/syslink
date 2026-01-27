<!DOCTYPE html>

<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        @include('backoffice.head')
        <!-- END: CSS Assets-->
    </head>
    <?php
    session()->forget('previous_url');
            session()->put('previous_url', url()->current());
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
                            <li class="breadcrumb-item active" aria-current="page">Job</li>
                        </ol>
                    </nav>
                    <!-- END: Breadcrumb -->
                    @include('backoffice.menu.account_menu')
                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
                <h2 class="intro-y text-lg font-medium mt-10">
                    JOB
                </h2>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                        @php
                            $type_project=DB::table('tb_project')->get();
                            $engineer=DB::table('users')->where('role','engineer')->get();
                            $sale=DB::table('users')->where('role','sale')->get();
                            $team=DB::table('tb_team')->get();
                        @endphp
                        {{-- <div class="hidden md:block mx-auto text-slate-500"></div> --}}
                        {{-- <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-slate-500"> --}}
                                <input type="text" class="form-control w-56 box pr-10" name="search" id="search" placeholder="Search..." value="{{@$search}}">
                                <select  class="tom-select" style="width: 140px" name="project" id="project" data-header="ประเภทงาน">
                                    <option value="All">All Project</option>
                                    @foreach ($type_project as $item)
                                    <option value="{{$item->id}}" @if ($projects == $item->id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <select  class="tom-select" style="width: 120px" name="status" id="status" data-header="Status">
                                    <option value="All">All Status</option>
                                    <option value="สำรวจ" @if (@$status == 'สำรวจ') selected @endif>สำรวจ</option>
                                    <option value="ติดตั้ง" @if (@$status == 'ติดตั้ง') selected @endif>ติดตั้ง</option>
                                    <option value="แก้ไข" @if (@$status == 'แก้ไข') selected @endif>แก้ไข</option>
                                </select>
                                <select  class="tom-select" style="width: 120px" name="sale" id="sale" data-header="Sale">
                                    <option value="All">All Sale</option>
                                    @foreach ($sale as $item)
                                    <option value="{{$item->id}}" @if (@$sales == $item->id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <select  class="tom-select" style="width: 120px" name="engineer" id="engineer" data-header="Engineer">
                                    <option value="All">All Engineer</option>
                                    @foreach ($engineer as $item)
                                    <option value="{{$item->id}}"@if (@$engineers == $item->id) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <select  class="tom-select" style="width: 140px" name="team" id="team" data-header="Team Technician">
                                    <option value="All">All Technician</option>
                                    @foreach ($team as $item)
                                    <option value="{{$item->id}}" @if (@$teams == $item->id) selected @endif>{{$item->team_name}}</option>
                                    @endforeach
                                </select>

                            <button type="button" class="btn btn-success" onclick="searchDoc('search','project','status','engineer','team','sale')">Search</button> &nbsp;
                            <a class="btn btn-primary shadow-md mr-2" {{ auth()->guard('admin')->user()->permission == 1 ? '' : 'disabled-link' }}
                                href="{{ auth()->guard('admin')->user()->permission == 1 ? url('/job/add') : '#' }}"
                                title="{{ auth()->guard('admin')->user()->permission == 1 ? '' : 'User does not have permission to edit data in the system' }}">Add New Job</a>
                            {{-- </div>
                        </div> --}}
                    </div>
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-responsive table-report -mt-2" id="myTable">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap"> # </th>
                                    <th class="whitespace-nowrap"> Job </th>
                                    <th class="whitespace-nowrap"> ประเภทงาน </th>
                                    <th class="whitespace-nowrap"> สถานะ </th>
                                    <th class="whitespace-nowrap"> ลูกค้า </th>
                                    <th class="whitespace-nowrap"> วิศวะ </th>
                                    <th class="whitespace-nowrap"> Survey Date </th>
                                    <th class="whitespace-nowrap"> ทีมช่าง </th>
                                    <th class="whitespace-nowrap"> Install Date </th>
                                    <th class="whitespace-nowrap"> Sale </th>
                                    <th class="whitespace-nowrap"> Service Date </th>
                                    <th class="whitespace-nowrap"> Manage </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($job as $key => $item)
                                <tr class="intro-x">
                                    <td>
                                        <div>
                                        {{ $job->firstItem() + $key }}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{$item->job_name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            @php
                                                $project=DB::table('tb_project')->where('id',$item->type)->first();
                                            @endphp
                                            @if (empty($project))
                                            Other
                                            @else
                                            {{@$project->name }}

                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex">
                                            @if ($item->status_job == 'in progress')
                                                <span class="text-xs px-1 bg-primary text-white mr-1">
                                            @elseif ($item->status_job == 'success')
                                                <span class="text-xs px-1 bg-success text-white mr-1">
                                            @elseif ($item->status_job == 'wait')
                                                <span class="text-xs px-1 bg-warning text-white mr-1">
                                            @elseif ($item->status_job == 'cancle')
                                                <span class="text-xs px-1 bg-danger text-white mr-1">
                                            @endif
                                            {{$item->status}}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{$item->customer_name}}
                                        </div>
                                    </td>
                                    @php
                                    $engineerIds = explode(',', $item->engineer);
                                    $engineer=DB::table('users')->whereIn('id',$engineerIds)->get();
                                    $technician=DB::table('tb_team')->where('id',$item->technician)->first();
                                    $sale=DB::table('users')->where('id',$item->sale)->first();
                                    // dd($technician);
                                    @endphp

                                    <td>
                                        <div>
                                            @if(!empty($engineer))
                                                @foreach ($engineer as $en)
                                                {{$en->name}} <br>
                                                @endforeach
                                            @else
                                                Engineer Not Assigned
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            @if (!empty($item->survay_date))
                                            {{ \Carbon\Carbon::parse($item->survay_date)->format('d-m-Y H:i') }}
                                            @else
                                            -
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            @if($technician)
                                                {{$technician->team_name}}
                                            @else
                                                Technician Not Assigned
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            @if (!empty($item->install_date))
                                            {{ \Carbon\Carbon::parse($item->install_date)->format('d-m-Y H:i') }}
                                            @else
                                            -
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{@$sale->name}}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            @if (!empty($item->service_date))
                                            {{ \Carbon\Carbon::parse($item->service_date)->format('d-m-Y H:i') }}
                                            @else
                                            -
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-report__action">
                                        <div class="flex justify-center">
                                            <a class="flex items-center mr-3" href="{{url('/job/submit_work')}}/{{$item->id}}">
                                                <i data-lucide="eye" class="block mx-auto"></i>
                                               </a>
                                            @if ( auth()->guard('admin')->user()->role == 'superadmin' ||   auth()->guard('admin')->user()->permission == 1)
                                            <a class="flex items-center mr-3" href="{{ url('/job/edit/'.$item->id) . '?' . http_build_query(request()->all()) }}">
                                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                            </a>
                                            <!-- <a class="flex items-center mr-3" href="{{url("/job/edit/$item->id")}}"><i data-lucide="edit" class="w-4 h-4 mr-1"></i></a> -->
                                            @endif
                                            @if ( auth()->guard('admin')->user()->role == 'superadmin' )

                                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview{{$item->id}}" class="flex items-center mr-3" style="color: red"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- END: Data List -->

                    <!-- BEGIN: Pagination -->
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                        <nav class="w-full sm:w-auto sm:mr-auto">
                            <ul class="pagination">
                                {{ $job->appends(request()->query())->links() }}
                            </ul>
                        </nav>
                        <select class="w-20 form-select box mt-3 sm:mt-0" id="show" name="show" onchange="updateShow(this.value)">
                            <option value="10" {{ request('show') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('show') == '25' ? 'selected' : '' }}>25</option>
                            <option value="35" {{ request('show') == '35' ? 'selected' : '' }}>35</option>
                            <option value="50" {{ request('show') == '50' ? 'selected' : '' }}>50</option>
                        </select>
                        
                        <script>
                        function updateShow(value) {
                            const url = new URL(window.location.href);
                            url.searchParams.set('show', value);
                            window.location.href = url.toString();
                        }
                        </script>
                    </div>
                    <!-- END: Pagination -->
                </div>

                <!-- START: Success Save Modal-->
                {{-- save --}}
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
                    {{-- delete --}}

                    <!-- BEGIN: Modal Content -->
                    @foreach ($job as $item)
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

                <!-- END: Success Save Modal-->
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
        {{-- <script>
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
        </script> --}}
        <script>
            function searchDoc(search,project,status,sale,engineer,team){
                window.location.replace('/job?search='+document.getElementById(search).value+'&project='+document.getElementById(project).value+'&status='+document.getElementById('status').value+'&sale='+document.getElementById('sale').value+'&engineer='+document.getElementById('engineer').value+'&team='+document.getElementById('team').value);
            }
        </script>
    </body>
</html>
