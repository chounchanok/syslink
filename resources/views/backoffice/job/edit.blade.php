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
                            <li class="breadcrumb-item"><a href="{{ url('/job') . '?' . http_build_query(request()->all()) }}">Job</a></li>
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
                        Edit {{$job->job_name}}
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>

                    <div class="intro-y col-span-12 lg:col-span-8">
                        <!-- BEGIN: Form Layout -->
                        <form action="{{url('/job/edit/sub')}}" method="POST" enctype="multipart/form-data" onsubmit="validatePhoneNumber()">
                            @csrf
                        <div class="intro-y box p-5">
                            <div class="mb-3">
                                <label class="form-label">Job</label>
                                <input id="crud-form-1" type="text" class="form-control" name="job_name" value="{{$job->job_name}}">
                            </div>
                            <input type="hidden" name="id" value="{{$job->id}}" id="">
                            <div class="mb-3">
                                <label class="form-label">ชื่อโปรเจค</label>
                                <select name="type_a" class="form-control" id="job">
                                    <option value="เลือก" disabled>เลือก</option>
                                    @php
                                        $isMatched = false;
                                    @endphp

                                    @foreach ($project as $item)
                                        <option value="{{$item->id}}" @if ($item->id == $job->type) selected @php $isMatched = true; @endphp @endif>{{$item->name}}</option>
                                    @endforeach

                                    <option value="39" @if ($job->type == 39 || !$isMatched) selected @endif>อื่นๆ</option>
                                </select>
                            </div><br>
                            <div class="mb-3">
                                <input type="text" name="other_detail" id="other_input" placeholder="ใส่ชื่อโปรเจค" class="form-control" @if (!empty($job->other_detail)) value="{{$job->other_detail}}" @else style="display: none;" @endif >

                            </div>
                            <div class="mb-3">
                                <label for="">หัวข้อส่งงานที่เพิ่ม</label>

                                @foreach ($submit as $item)
                                <input type="text" class="form-control" name="inputs_edit[]" value="{{$item->name}}" id=""> <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview{{$item->id}}" class="flex items-center mr-3" style="color: red"> ลบ</a>

                                @endforeach
                            </div>
                            <div id="input-container">

                                <!-- ที่นี่คือที่ที่ช่อง input ใหม่ ๆ จะถูกเพิ่ม -->
                            </div>
                            <button type="button" class="btn btn-primary shadow-md mr-2 mb-3" id="add-input-btn">เพิ่ม หัวข้อส่งงาน</button>
                            <div class="mb-3">
                                <label class="form-label">Phase</label>
                                <select name="phase" class="form-control" id="phase">
                                    <option value="1 Phase" @if ($job->phase=="1 phase") selected @endif>1 Phase</option>
                                    <option value="3 Phase" @if ($job->phase=="3 phase") selected @endif>3 Phase</option>
                                    <option value="other" @if ($job->phase=="other"||$job->phase=="อื่นๆ") selected @endif>อื่นๆ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="other_detail_phase" id="other_input_phase"  placeholder="ใส่ phase ที่ต้องการ" class="form-control" @if (!empty($job->other_phase)) value="{{$job->other_phase}}" @else style="display: none;" @endif  >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label><br>
                                <input type="radio" name="status" value="สำรวจ" @if ($job->status == 'สำรวจ') checked @endif>
                                <label>สำรวจ</label>
                                <label class="form-label">Survey Date :</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="survay_date" type="datetime-local" class="form-control" style="width: 200px" @if ($job->status_job == 'success' and $job->status == 'สำรวจ') readonly @endif name="survay_date" value="{{ $job->survay_date }}">

                            </div>
                            <div class="mb-3">
                                <input type="radio" name="status" value="ติดตั้ง" @if ($job->status == 'ติดตั้ง') checked @endif>
                                <label>ติดตั้ง</label>
                                <label class="form-label">Install Date :</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="install_date" type="datetime-local" class="form-control" style="width: 200px" @if ($job->status_job == 'success' and $job->status == 'ติดตั้ง') readonly @endif name="install_date" value="{{ $job->install_date }}">
                            </div>
                            <div class="mb-3">
                                <input type="radio" name="status" value="แก้ไข" @if ($job->status == 'แก้ไข') checked @endif>
                                <label>แก้ไข</label>
                                <label class="form-label">Service Date :</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="service_date" type="datetime-local" class="form-control" style="width: 200px" name="service_date" value="{{ $job->service_date }}">
                            </div>
                            <br>
                            @php
                            $sale=DB::table('users')->where('role','sale')->get();
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">Sale</label>
                                <select name="sale[]" id="" class="tom-select" multiple>
                                    <option value="" disabled>-- โปรเลือก --</option>
                                    @php
                                        $sale_in = is_array($job->sale) ? $job->sale : explode(',', $job->sale);
                                    @endphp
                                    @foreach ($sale as $item)
                                    <option value="{{$item->id}}" @if(in_array($item->id,$sale_in)) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                @php
                                $inspect=DB::table('users')->where('role','engineer')->get();
                                $engineer=DB::table('tb_team')->get();
                            @endphp
                                <label class="form-label">วิศวกร</label>
                                <select name="engineer[]" class="tom-select" multiple id="job">
                                    <option value="เลือก">-- โปรเลือก --</option>
                                    @if (!empty($inspect))
                                    @php
                                        // แปลงค่า $job->engineer ให้เป็นอาเรย์ถ้ายังไม่เป็น
                                        $engineerIds = is_array($job->engineer) ? $job->engineer : explode(',', $job->engineer);
                                        // dd($ip->id);
                                    @endphp
                                        @foreach (@$inspect as $ip)
                                            <option value="{{@$ip->id}}" @if (in_array($ip->id, $engineerIds)) selected @endif >{{@$ip->name}}</option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">ทีมช่าง</label>
                                <select name="technician" class="form-control" id="job">
                                    <option value="เลือก">-- โปรเลือก --</option>
                                    @if (!empty($engineer))
                                        @foreach ($engineer as $eg)
                                            <option value="{{$eg->id}}" @if ($eg->id == $job->technician) selected @endif >{{$eg->team_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            {{-- <div>
                                <label class="form-label">สำรวจ|ติดตั้ง</label><br>
                                <input type="radio" name="status" value="สำรวจ" @if ($job->status == 'สำรวจ') checked @endif>
                                <label>สำรวจ</label>
                                <br>
                                <input type="radio" name="status" value="ติดตั้ง" @if ($job->status == 'ติดตั้ง') checked @endif>
                                <label>ติดตั้ง</label>
                                <br>
                                <input type="radio" name="status" value="แก้ไข" @if ($job->status == 'แก้ไข') checked @endif>
                                <label>แก้ไข</label>
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label">ลูกค้า</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="customer"  value="{{$job->customer_name}}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">เบอร์โทรติดต่อ</label><p id="errorMessage" style="color:red;"></p>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="tell" id="tell" oninput="limitInputLength(event)" value="{{$job->tell}}">
                            </div>
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label for="provinces">จังหวัด</label> <br>
                                    <select name="provinces" id="provinces" class="tom-select"  onchange="loadDistricts(this.value)">
                                    <option value="">เลือก</option>
                                    @foreach ($province as $item)
                                            <option value="{{$item->id}}"@if ($job->province == $item->id) selected @endif>{{$item->name_th}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="districts">อำเภอ</label> <br>
                                    <select name="districts" id="districts" class="form-control" >
                                        <option value="">เลือก</option>
                                        @foreach ($district as $item)
                                        <option value="{{$item->id}}"@if ($job->district == $item->id) selected @endif>{{$item->name_th}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <label class="form-label">ที่อยู่</label>

                                <textarea name="address" id="" class="form-control" required>{{$job->address_customer}}</textarea>

                            </div>
                            <div class="mb-3">
                                <label class="form-label">Map</label><br>
                                <input id="crud-form-1" type="text" class="form-control w-full" name="google_map" id="google_map" value="{{$job->google_map}}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">รายละเอียดงานเพิ่มเติม</label>
                                <textarea id="crud-form-1" class="form-control w-full"  name="job_detail" >{{$job->job_detail}}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">เพิ่มรูปภาพ</label>
                                <input id="crud-form-1" type="file" multiple class="form-control w-full" name="file_detail[]" >
                            </div>
                            <br>
                            <div class="flex flex-wrap px-4 mb-3">
                                @foreach ($job_file as $jf)
                                <div class="relative image-fit mb-5 mr-5 cursor-pointer">
                                    <a class="btn btn-elevated-secondary mr-1 mb-2" target="_blank" href="{{ asset("$jf->path"."$jf->file_name")}}"><i data-lucide="file-text"></i> {{$jf->file_name}}</a>
                                    <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-job-file{{$jf->id}}" class="flex items-center mr-3" style="color: red">
                                        <div title="Remove this file?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="x" class="w-4 h-4"></i> </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="flex flex-wrap px-4 mb-3">
                                @foreach ($job_img as $ji)
                                    <div class="w-24 h-24 relative image-fit mb-5 mr-5 cursor-pointer zoom-in">
                                        <img class="rounded-md" alt="Midone - HTML Admin Template" src="{{ asset("$ji->path"."$ji->file_name")}}" >
                                    <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-job-img{{$ji->id}}" class="flex items-center mr-3" >
                                        <div title="Remove this image?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-lucide="x" class="w-4 h-4"></i> </div>
                                    </a>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label for=""> ลูกค้ายกเลิก job :</label>
                                <input type="checkbox" name="status_job" id="cancle" @if ($job->status_job == 'cancle') checked @endif>

                            </div>
                            <div @if ($job->status_job != 'cancle')
                                style="display: none;"
                                @else
                                style="display: block;"
                                @endif style="display: none;" id="note" class="mb-3">
                                <label >หมายเหตุ</label>
                                <input type="text" name="note" value="{{$job->note}}" class="form-control" >
                            </div>
                            <div class="text-right mt-5">
                                <a type="button" href="{{ url('/job') . '?' . http_build_query(request()->all()) }}" class="btn btn-outline-secondary w-24 mr-1">Cancel</a>
                                {{-- <a type="button" href="{{url('/job')}}" class="btn btn-outline-secondary w-24 mr-1">Cancel</a> --}}
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
                @foreach ($job_file as $item)
                    <div id="delete-job-file{{$item->id}}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <form action="{{url('/job/file/delete')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <div class="p-5 text-center"> <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">Are you sure?</div>
                                        <div class="text-slate-500 mt-2">Do you really want to delete these File? <br>This process cannot be undone.</div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$item->id}}" id="">
                                    <input type="hidden" name="job_id" value="{{$item->job_id}}" id="">
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
                @foreach ($job_img as $item)
                    <div id="delete-job-img{{$item->id}}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <form action="{{url('/job/file/delete')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <div class="p-5 text-center"> <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">Are you sure?</div>
                                        <div class="text-slate-500 mt-2">Do you really want to delete these Image? <br>This process cannot be undone.</div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$item->id}}" id="">
                                    <input type="hidden" name="job_id" value="{{$item->job_id}}" id="">
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
                @foreach ($submit as $item)
                <div id="delete-modal-preview{{$item->id}}" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <form action="{{url('project/submit_delete')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="url_return" value="/job/edit/{{$job->id}}" id="">
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

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            input.className = 'form-control mb-3'; // กำหนด class เป็น form-control

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
<script>
    function loadDistricts(provinceId) {
        // Reset dropdown อำเภอ
        document.getElementById('districts').innerHTML = '<option value="">เลือกอำเภอ</option>';
        // alert(provinceId)
        if (provinceId) {
            $.ajax({
                url: '/districts/' + provinceId, // ดึงข้อมูลอำเภอโดยใช้ provinceId
                type: 'GET',
                success: function(data) {
                    // alert(data);
                    // $('#districts').prop('disabled', false); // เปิดใช้งาน select ของอำเภอ
                    $.each(data, function(index, district) {
                        $('#districts').append('<option value="' + district.id + '">' + district.name_th + '</option>');
                    });
                },
                error: function() {
                    console.error('Failed to load districts');
                }
            });
        } else {
            $('#districts').prop('disabled', true); // ปิดใช้งาน select ของอำเภอถ้าไม่ได้เลือกจังหวัด
        }
    }
</script>

@if ( auth()->guard('admin')->user()->role != 'superadmin' )

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const surveyDateInput = document.getElementById('survay_date');
        const installDateInput = document.getElementById('install_date');
        const serviceDateInput = document.getElementById('service_date');

        // ไม่ต้องตั้งค่า minimum date/time แล้ว
        // function setMinimumDateTime() {
        //     const currentDateTime = new Date().toISOString().slice(0, 16);
        //     surveyDateInput.setAttribute('min', currentDateTime);
        //     installDateInput.setAttribute('min', currentDateTime);
        //     serviceDateInput.setAttribute('min', currentDateTime);
        // }
        // setMinimumDateTime();

        // ลบฟังก์ชัน validateDateTime ไปเลย หรือปล่อยว่างไว้
        function validateDateTime(input) {
            // ไม่ต้องตรวจสอบว่า input.value < min
        }

        // Handle survey date change
        surveyDateInput.addEventListener('change', function () {
            const surveyDateValue = surveyDateInput.value;

            // ไม่ต้องตรวจสอบว่า surveyDateValue < currentDateTime
            // เพียงแค่ตั้งค่า min ของ install และ service เท่ากับ survey ก็พอ
            installDateInput.setAttribute('min', surveyDateValue);
            serviceDateInput.setAttribute('min', surveyDateValue);
        });

        // Handle install date change
        installDateInput.addEventListener('change', function () {
            validateDateTime(installDateInput);

            const installDateValue = installDateInput.value;
            const surveyDateValue = surveyDateInput.value;

            if (installDateValue) {
                // ตั้งค่า service_date min เป็นค่าที่มากสุดระหว่าง survey และ install
                const latestMin = installDateValue > surveyDateValue ? installDateValue : surveyDateValue;
                serviceDateInput.setAttribute('min', latestMin);
            }
        });

        // Handle service date change
        serviceDateInput.addEventListener('change', function () {
            validateDateTime(serviceDateInput);
        });
    });
</script>

@endif

    </body>

</html>
