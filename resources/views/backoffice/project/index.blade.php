<!DOCTYPE html>

<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        @include('backoffice.head')
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
                            <li class="breadcrumb-item active" aria-current="page">Project</li>
                        </ol>
                    </nav>
                    <!-- END: Breadcrumb -->
                    @include('backoffice.menu.account_menu')
                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
                <h2 class="intro-y text-lg font-medium mt-10">
                    Project
                </h2>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                        <form action="{{route('project.create')}}" method="POST" enctype="multipart/form-data">
                            {{-- <div class="w-56 relative text-slate-500"> --}}
                                @csrf
                                <label for="">project name</label>
                                <input class="form-control" type="text" name="name" required id="" {{auth()->guard('admin')->user()->permission == 1 ? null:'disabled' }}>
                                <label for="">color</label><br>
                                <input type="color" name="color" required id="" {{auth()->guard('admin')->user()->permission == 1 ? null:'disabled' }}><br>
                            <button class="btn btn-primary shadow-md mr-2" {{auth()->guard('admin')->user()->permission == 1 ? null:'disabled' }}
                                title="{{ auth()->guard('admin')->user()->permission == 1 ? '' : 'User does not have permission to edit data in the system' }}">Add project name</button>
                            {{-- </div> --}}
                        </form>
                        <div class="hidden md:block mx-auto text-slate-400"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-slate-500">
                                <form action="{{route('project.search')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="relative w-56">
                                        <input type="text" class="form-control w-full box pr-10" name="search" id="search" value="{{ @$search }}" placeholder="Search...">
                                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3">
                                            <i class="w-4 h-4" data-lucide="search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2" id="myTable">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap"> # </th>
                                    <th class="whitespace-nowrap"> Name </th>
                                    <th class="whitespace-nowrap text-center"> Number of submission topics </th>
                                    <th class="whitespace-nowrap"> Color </th>
                                    <th class="whitespace-nowrap"> Create time </th>
                                    <th class="whitespace-nowrap"> Update time </th>
                                    <th class="whitespace-nowrap"> Manage </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($project as $key => $item)
                                <tr class="intro-x">
                                    <td>
                                        <div>
                                            {{ $project->firstItem() + $key }}
                                        </div>
                                    </td>
                                    @php
                                        $project_submit = \App\Models\Project_submit::where('project_id',$item->id)->whereNull('job_id')->count();
                                    @endphp
                                    <td>
                                        <div>
                                            {{$item->name}} 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center text-center justify-center">
                                            @if($project_submit > 0)
                                            <span class="text-slate-500">{{$project_submit}}</span>
                                            @else
                                            <span style="color: red;">ไม่พบหัวข้อส่งงาน กรุณาเพิ่มหัวข้อส่งงาน</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <input type="color" disabled value="{{$item->color}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i')}}
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i')}}
                                        </div>
                                    </td>

                                    <td class="table-report__action">
                                        <div class="flex justify-center">
                                            <a class="flex items-center mr-3" href="{{route('project.add',$item->id)}}">
                                                + add submit
                                            </a>
                                            @if (auth()->guard('admin')->user()->role == 'superadmin' || auth()->guard('admin')->user()->permission == 1 )

                                            <a class="flex items-center mr-3" href="{{route('project.edit',$item->id)}}"><i data-lucide="check-square" class="w-4 h-4 mr-1"></i>Edit</a>
                                            @endif
                                            @if ( auth()->guard('admin')->user()->role == 'superadmin')
                                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview{{$item->id}}" class="flex items-center mr-3" style="color: red"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete</a>
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
                                {{ $project->links() }}
                            </ul>
                        </nav>
                        <select class="w-20 form-select box mt-3 sm:mt-0" id="show" name="show" onchange="document.location.href=`{{url('/project')}}?show=${this.value}`" >
                            <option value="10" {{@$show=='10'?'selected':''}}>10</option>
                            <option value="25" {{@$show=='25'?'selected':''}}>25</option>
                            <option value="35" {{@$show=='35'?'selected':''}}>35</option>
                            <option value="50" {{@$show=='50'?'selected':''}}>50</option>
                        </select>
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
                    @foreach ($project as $item)
                    <div id="delete-modal-preview{{$item->id}}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <form action="{{url('/project/delete')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <div class="p-5 text-center"> <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">Are you sure?</div>
                                        <div class="text-slate-500 mt-2">Do you really want to delete these records? <br>This process cannot be undone.</div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$item->id}}" id="">
                                    <div class="px-5 pb-8 text-center">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                        <button class="btn btn-danger w-24" type="submit">Delete</button>
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
    </body>
</html>
