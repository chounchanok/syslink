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
                            <li class="breadcrumb-item"><a href="/job">Job</a></li>
                            <li class="breadcrumb-item"><a href="/job/submit_work/{{$id}}">View</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Album {{$name}}</li>
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
                        Job View {{$job->job_name}}
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Form Layout -->
                        <div class="intro-y box p-5">
                            <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                                @if ($album->isEmpty())
                                    <div class="intro-y col-span-12 sm:col-span-12">
                                        <div class="text-center text-gray-500">ไม่มีข้อมูลในอัลบั้มนี้</div>
                                    </div>
                                    
                                @else
                                    
                            @foreach ($album as $item)
                                @if ($item->type == 'pdf')
                                <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 2xl:col-span-3">
                                    <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                        <a href="{{url($item->path.$item->file_name)}}" target="_blank" class="w-3/5 file__icon file__icon--file mx-auto">
                                            <div class="file__icon__file-name">PDF</div>
                                        </a>
                                        <a href="{{url($item->path.$item->file_name)}}" target="_blank" class="block font-medium mt-4 text-center truncate">{{$item->file_name}}</a>
                                    </div>
                                </div>
                                @else
                                <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 2xl:col-span-3">
                                    <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                        <a href="{{url($item->path.$item->file_name)}}" target="_blank" class="w-3/5 file__icon file__icon--image mx-auto">
                                            <div class="file__icon--image__preview image-fit">
                                                <img alt="Midone - HTML Admin Template" src="{{asset($item->path.$item->file_name)}}">
                                            </div>
                                        </a>
                                        <a href="{{url($item->path.$item->file_name)}}" target="_blank" class="block font-medium mt-4 text-center truncate">{{$item->file_name}}</a>

                                    </div>
                                </div>
                                @endif
                            @endforeach
                                @endif

                            </div>
                        </div>
                        <!-- END: Form Layout -->
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
        document.getElementById("phase").addEventListener("change", function() {
            var option = this.value;
            // เลือก select ที่ต้องการเปลี่ยนแปลง

            if (option === 'other') {
                document.getElementById('other_input_phase').style.display = 'block';
            }
            else {
                document.getElementById('other_input_phase').style.display = 'none';

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addInputBtn = document.getElementById('add-input-btn');
        const inputContainer = document.getElementById('input-container');

        addInputBtn.addEventListener('click', function() {
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'inputs[]';
            input.className = 'form-control'; // กำหนด class เป็น form-control

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn';
            removeBtn.textContent = 'ลบ';

            removeBtn.addEventListener('click', function() {
                inputContainer.removeChild(inputGroup);
            });

            inputGroup.appendChild(input);
            inputGroup.appendChild(removeBtn);
            inputContainer.appendChild(inputGroup);
        });
    });
</script>
    </body>

</html>
