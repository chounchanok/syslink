<!DOCTYPE html>
<!--
Template Name: Midone - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>

        <!-- END: CSS Assets-->
        @include('backoffice.head')
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
                            <li class="breadcrumb-item"><a href="/backoffice/project">Project</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                    <!-- END: Breadcrumb -->
                    <!-- BEGIN: Search -->
                    <!-- END: Search -->
                    <!-- BEGIN: Notifications -->
                    {{-- @include('menu.notifications') --}}
                    <!-- END: Notifications -->
                    <!-- BEGIN: Account Menu -->
                    @include('backoffice.menu.account_menu')
                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Edit {{$project->name}}
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>

                    <div class="intro-y col-span-12 lg:col-span-8">
                        <!-- BEGIN: Form Layout -->
                        <form action="{{route('project.update')}}" method="POST" enctype="multipart/form-data"">
                            @csrf
                        <div class="intro-y box p-5">
                            <input type="hidden" name="id" value="{{$project->id}}" id="">
                            <div>
                                <label class="form-label">name</label>
                                <input id="crud-form-1" type="text" class="form-control" name="name" value="{{$project->name}}">
                            </div>
                            <div>
                                <label class="form-label">color</label>
                                <input id="crud-form-1" type="color" class="form-control" name="color" value="{{$project->color}}">
                            </div>

                            <div class="text-right mt-5">
                                <a type="button" href="{{route('project')}}" class="btn btn-outline-secondary w-24 mr-1">Cancel</a>
                                <button type="submit" class="btn btn-primary w-24">Save</button>
                            </div>


                        </div>
                        </form>
                        <!-- END: Form Layout -->
                    </div>
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>


                </div>
            </div>
            <!-- END: Content -->
            {{-- <!-- START: Success Save Modal-->
            <div class="text-center"style="display: none" >
                <button href="javascript:;" data-tw-toggle="modal" data-tw-target="#button-modal-preview" class="btn btn-primary"  id="auto-click-button">Show Modal</button>
            </div>
            <!-- END: Modal Toggle -->
            <!-- BEGIN: Modal Content -->
            <div id="button-modal-preview" class="modal" tabindex="-1" >
                <div class="modal-dialog">
                    <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i> </a>
                        <div class="modal-body p-0">
                            <div class="p-5 text-center"> <i data-lucide="check-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
                                <div class="text-3xl mt-5">บันทึก</div>
                            </div>
                            <div class="px-5 pb-8 text-center"> <button type="button" data-tw-dismiss="modal" class="btn btn-primary w-24">Ok</button> </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            @if (!empty(session('success')))
            <script>
                // เมื่อหน้าเว็บโหลดเสร็จ ให้คลิกปุ่มโดยอัตโนมัติ
                window.onload = function() {
                    document.getElementById('auto-click-button').click();
                };
            </script>
            @endif
        </div>

        @include('backoffice.js.js')

    {{-- <script>
        document.getElementById("job").addEventListener("change", function() {
            var selectedValue = this.value; // ค่าที่ถูกเลือกใน select แรก

            // เลือก select ที่ต้องการเปลี่ยนแปลง
            var secondSelect = document.getElementById("phase");
            // ลบตัวเลือกทั้งหมดใน select ที่จะถูกเปลี่ยนแปลง
            secondSelect.innerHTML = '';
            // เพิ่มตัวเลือกใหม่ลงใน select ที่จะถูกเปลี่ยนแปลง
            if (selectedValue === "MG") {
                secondSelect.innerHTML += '<option value="1 Phase">1 Phase</option>';
                secondSelect.innerHTML += '<option value="อื่นๆ">อื่นๆ</option>';
            } else  {
                secondSelect.innerHTML += '<option value="1 Phase">1 Phase</option>';
                secondSelect.innerHTML += '<option value="3 Phase">3 Phase</option>';
                secondSelect.innerHTML += '<option value="อื่นๆ">อื่นๆ</option>';
            }
        });
    </script> --}}
    <script>
        document.getElementById("job").addEventListener("change", function() {
            var option = this.value;
            // เลือก select ที่ต้องการเปลี่ยนแปลง

            if (option === 'other') {
                document.getElementById('other_input').style.display = 'block';
            }
            else {
                document.getElementById('other_input').style.display = 'none';

            }
        });
    </script>
    <script>
        document.getElementById("cancle").addEventListener("change", function() {
            // กดเช็ค cancle
            if ( this.checked ) {
                document.getElementById('note').style.display = 'block';
            }
            else {
                document.getElementById('note').style.display = 'none';

            }
        });
    </script>
        <script>
            function limitInputLength(event) {
                let input = event.target.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');

                if (input.length < 10) {
                    document.getElementById('errorMessage').innerText = 'โปรดระบุเบอร์โทรศัพท์ให้ครบ';
                } else {
                    document.getElementById('errorMessage').innerText = '';
                }

                event.target.value = input.substring(0, 10);
            }
        </script>

    </body>

</html>
