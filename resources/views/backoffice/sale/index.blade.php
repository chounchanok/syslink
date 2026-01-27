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
                            <li class="breadcrumb-item active" aria-current="page">Sale</li>
                        </ol>
                    </nav>
                    <!-- END: Breadcrumb -->
                    @include('backoffice.menu.account_menu')
                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
                <h2 class="intro-y text-lg font-medium mt-10">
                    Sale
                </h2>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">


                        {{-- <div class="hidden md:block mx-auto text-slate-500"></div> --}}
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            {{-- <div class="w-56 relative text-slate-500"> --}}
                                <input type="text" class="form-control w-56 box pr-10" name="search" id="search" value="{{@$search}}"placeholder="Search...">
                                <button type="button" class="btn btn-success" onclick="searchDoc('search')">Search</button> &nbsp;
                                <a class="btn  shadow-md mr-2 {{ auth()->guard('admin')->user()->role == 'superadmin' ? 'btn-primary' : 'btn-secondary disabled-link' }}"
                                    href="{{ auth()->guard('admin')->user()->role == 'superadmin' ? url('/sale/add') : '#' }}">Add New Sale</a>
                                {{-- <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> --}}
                            {{-- </div> --}}
                        </div>
                    </div>
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">USERNAME</th>
                                    <th class="whitespace-nowrap">NAME</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale as $ad)

                                <tr class="intro-x">
                                    {{-- <td class="w-40">
                                        <div class="flex">
                                            <div class="w-10 h-10 image-fit zoom-in">
                                                <img alt="Midone - HTML Admin Template" class="tooltip rounded-full" src="{{asset('dist/images/preview-3.jpg')}}">
                                            </div>
                                        </div>
                                    </td> --}}
                                    <td class="w-60">
                                        <div class="w-60 h-10 flex items-center">
                                            {{$ad->username}}
                                        </div>
                                    </td>
                                    <td class="w-60">
                                        <div class="w-60 h-10 flex items-center">
                                            {{$ad->name}}
                                        </div>
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            @php
                                                $admin_now=Auth::guard('admin')->user();
                                                $check_role=$admin_now->role;
                                            @endphp
                                            @if ($check_role == 'superadmin'||auth()->guard('admin')->user()->permission == 1)
                                            <a class="flex items-center mr-3" href="{{url("/sale/edit/$ad->id")}}"><i data-lucide="check-square" class="w-4 h-4 mr-1"></i>Edit</a>
                                            @endif
                                            @if ($check_role == 'superadmin')
                                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-engineer{{$ad->id}}" class="flex items-center mr-3" style="color: red"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete</a>
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
                                {{ $sale->links() }}
                            </ul>
                        </nav>
                        {{-- <select class="w-20 form-select box mt-3 sm:mt-0" id="show" name="show" onchange="document.location.href=`{{url('/backoffice/user')}}?show=${this.value}`" >
                            <option value="10" {{@$show=='10'?'selected':''}}>10</option>
                            <option value="25" {{@$show=='25'?'selected':''}}>25</option>
                            <option value="35" {{@$show=='35'?'selected':''}}>35</option>
                            <option value="50" {{@$show=='50'?'selected':''}}>50</option>
                        </select> --}}
                    </div>
                    <!-- END: Pagination -->
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
                <!-- BEGIN: Delete Confirmation Modal -->
                @foreach ($sale as $item)
                <div id="delete-engineer{{$item->id}}" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{url("/sale/delete")}}" method="post">
                                @csrf
                            <div class="modal-body p-0">
                                <div class="p-5 text-center">
                                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                    <div class="text-3xl mt-5">Are you sure?</div>
                                    <div class="text-slate-500 mt-2">
                                        Do you really want to delete these records?
                                        <br>
                                        This process cannot be undone.
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$item->id}}">
                                <div class="px-5 pb-8 text-center">
                                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                    <button class="btn btn-danger w-24" type="submit">Delete</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- END: Delete Confirmation Modal -->
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
            function searchDoc(search,project,status,sale,engineer,team){
                window.location.replace('/sale?search='+document.getElementById(search).value);
            }
        </script>
    </body>
</html>
