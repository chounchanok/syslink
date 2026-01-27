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
                            <li class="breadcrumb-item active" aria-current="page">Add</li>
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
                        Add New Job
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>

                    <div class="intro-y col-span-12 lg:col-span-8">
                        <!-- BEGIN: Form Layout -->
                        <form action="{{url('/job/add/sub')}}" method="POST" enctype="multipart/form-data" onsubmit="validatePhoneNumber()">
                            @csrf
                        <div class="intro-y box p-5">
                            <div class="mb-3">
                                <label class="form-label">Job</label>
                                <input id="crud-form-1" type="text" class="form-control" required name="job_name">
                            </div>
                            @php
                                $submittedProjectIds = \App\Models\Project_submit::pluck('project_id')->toArray(); // สมมุติว่า project_submit เป็น collection
                            @endphp

                            <div class="mb-3">
                                <label class="form-label">ชื่อโปรเจค</label>
                                <select name="type_a" class="form-control" id="job">
                                    <option value="เลือก">เลือก</option>
                                    @foreach ($project as $item)
                                        @php
                                            $needsSubmit = !in_array($item->id, $submittedProjectIds);
                                        @endphp
                                        @if ($item->id != 39)                                      
                                            <option 
                                                value="{{ $item->id }}" 
                                                @if ($needsSubmit) data-requires-submit="1" @endif
                                            >
                                                {{ $item->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                    <option value="39" data-requires-submit="1">อื่นๆ</option>
                                </select>
                            </div><br>
                            <script>
                                document.getElementById('job').addEventListener('change', function () {
                                    const selectedOption = this.options[this.selectedIndex];
                                    const requiresSubmit = selectedOption.getAttribute('data-requires-submit');
                            
                                    const addInputBtn = document.getElementById('add-input-btn');
                                    if (requiresSubmit === '1') {
                                        addInputBtn.classList.remove('d-none');
                                    } else {
                                        addInputBtn.classList.add('d-none');
                                    }
                                });
                            </script>

                            <div class="mb-3">
                                <input type="text" name="other_detail" id="other_input" placeholder="ใส่ชื่อโปรเจค" class="form-control" style="display: none;">
                            </div>
                            <div id="input-container">
                                <!-- ที่นี่คือที่ที่ช่อง input ใหม่ ๆ จะถูกเพิ่ม -->
                            </div>

                            <button type="button" class="btn btn-primary shadow-md mr-2 mb-3" id="add-input-btn">เพิ่ม หัวข้อส่งงาน</button>
                            <div class="mb-3">
                                <label class="form-label">Phase</label>
                                <select name="phase" class="form-control" id="phase">
                                    <option value="1 Phase">1 Phase</option>
                                    <option value="3 Phase">3 Phase</option>
                                    <option value="other">อื่นๆ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="other_detail_phase" id="other_input_phase" placeholder="ใส่ phase ที่ต้องการ" class="form-control" style="display: none;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label><br>
                                <input type="radio" name="status" value="สำรวจ" checked>
                                <label>สำรวจ</label>
                                <label class="form-label">Survey Date : </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="survay_date" type="datetime-local" class="form-control" style="width: 200px" name="survay_date" >
                            </div>
                            <div class="mb-3">
                                <input type="radio" name="status" value="ติดตั้ง">
                                <label>ติดตั้ง</label>
                                <label class="form-label">&nbsp; Install Date : </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="install_date" type="datetime-local" class="form-control" style="width: 200px" name="Install_date" >
                            </div>
                            <div class="mb-3">
                                <input type="radio" name="status" value="แก้ไข">
                                <label>แก้ไข</label>
                                <label class="form-label">&nbsp;Service Date : </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="service_date" type="datetime-local" class="form-control" style="width: 200px" name="Service_date">
                            </div>
                            <br>
                            @php
                            $sale=DB::table('users')->where('role','sale')->get();
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">Sale</label>
                                <select name="sale[]" id="" class="tom-select" multiple>
                                    <option value="" >-- โปรเลือก --</option>
                                    @foreach ($sale as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                @php
                                    $inspect=DB::table('users')->where('role','engineer')->get();
                                    $engineer=DB::table('tb_team')->get();
                                @endphp
                                <label class="form-label">วิศวกร</label>
                                <select name="engineer[]" class="tom-select" id="job" multiple>
                                    <option value="เลือก">-- โปรเลือก --</option>
                                    @if (!empty($inspect))
                                        @foreach (@$inspect as $ip)
                                            <option value="{{@$ip->id}}">{{@$ip->name}}</option>
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
                                            <option value="{{$eg->id}}">{{$eg->team_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ลูกค้า</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="customer" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">เบอร์โทรติดต่อ</label><p id="errorMessage" style="color:red;"></p>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="tell" id="tell" oninput="limitInputLength(event)">
                            </div><br>
                            <div class="mb-3">
                                <label for="provinces">จังหวัด</label> <br>
                                <select name="provinces" id="provinces" class="tom-select"  onchange="loadDistricts(this.value)">
                                    <option value=""> เลือกจังหวัด</option>
                                    @foreach ($province as $item)
                                        <option value="{{$item->id}}">{{$item->name_th}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="districts">อำเภอ</label> <br>
                                <select name="districts" id="districts" class="form-control" >
                                    <option value="">เลือก</option>
                                    {{-- @foreach ($district as $item)
                                        <option value="{{$item->id}}">{{$item->name_th}}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ที่อยู่</label>
                                <textarea name="address" id="" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="google_map">Map</label><br>
                                <input id="crud-form-1" type="text" placeholder="google map" class="form-control w-full" name="google_map" id="google_map">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">รายละเอียดงานเพิ่มเติม</label>
                                <textarea id="crud-form-1" class="form-control w-full"  name="job_detail" ></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">เพิ่มรูปภาพ</label><br>
                                <input id="crud-form-1" type="file" multiple name="file_detail[]" >
                            </div>

                            <div class="text-right mt-5">
                                <a type="button" href="{{url('/job')}}" class="btn btn-outline-secondary w-24 mr-1">Cancel</a>
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
        </div>

        @include('backoffice.js.js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const surveyDateInput = document.getElementById('survay_date');
        const installDateInput = document.getElementById('install_date');
        const serviceDateInput = document.getElementById('service_date');

        // Set the minimum date and time to now (no past dates or times allowed)
        function setMinimumDateTime() {
            const currentDateTime = new Date().toISOString().slice(0, 16);
            surveyDateInput.setAttribute('min', currentDateTime);
            installDateInput.setAttribute('min', currentDateTime);
            serviceDateInput.setAttribute('min', currentDateTime);
        }

        setMinimumDateTime();

        // Function to validate date and time
        function validateDateTime(input) {
            const minValue = input.getAttribute('min');
            if (input.value && input.value < minValue) {
                alert('You cannot select a past date or time.');
                input.value = minValue; // Reset to the current minimum value
            }
        }

        // Handle survey date change
        surveyDateInput.addEventListener('change', function () {
            const currentDateTime = new Date().toISOString().slice(0, 16);
            const surveyDateValue = surveyDateInput.value;

            // Recheck if survey date is in the past
            if (surveyDateValue && surveyDateValue < currentDateTime) {
                alert('Survey date and time cannot be in the past.');
                surveyDateInput.value = currentDateTime; // Reset to the current date and time
            }

            // Set install date's and service date's minimum to survey date
            installDateInput.setAttribute('min', surveyDateInput.value);
            serviceDateInput.setAttribute('min', surveyDateInput.value);
        });

        // Handle install date change
        installDateInput.addEventListener('change', function () {
            validateDateTime(installDateInput);

            const installDateValue = installDateInput.value;
            const surveyDateValue = surveyDateInput.value;

            if (installDateValue) {
                // Set service date's min as the latest between survey and install dates
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



    </body>

</html>
