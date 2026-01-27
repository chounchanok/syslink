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
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

        <style>
            .h-set{
                height: 150px;
            }
            #map {
                height: 400px;
                width: 100%;
            }
        </style>
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
                            <li class="breadcrumb-item active" aria-current="page">ส่งงาน </li>
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
                        {{@$job->job_name}}
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>

                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Tesla report -->
                            @csrf
                        <div class="intro-y box p-5">
                            {{-- <div>
                                <label class="form-label"> ชื่อลูกค้า : {{$job->customer_name}} </label>
                            </div> --}}
                            <ul class="nav nav-tabs" role="tablist">
                                <li id="admin-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#admin-tab" type="button" role="tab" aria-controls="example-tab-1" aria-selected="true"> Detail </button> </li>
                                <li id="install-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#install-tab" type="button" role="tab" aria-controls="example-tab-2" aria-selected="false"> ส่งงาน </button> </li>
                            </ul>
                            <div class="tab-content border-l border-r border-b">
                                <div id="admin-tab" class="tab-pane leading-relaxed p-5 active" role="tabpanel" aria-labelledby="admin-tab">

                                    <div>
                                        <label class="form-label"> Job Name : {{$job->job_name}} </label>
                                    </div>
                                    <div>
                                        @php
                                            $project = DB::table('tb_project')->where('id',$job->type)->first();
                                        @endphp
                                        <label class="form-label"> Project Name : {{$project != null ? $project->name : "Other"}} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> phase : {{$job->phase}} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> Status : {{$job->status}} </label>
                                    </div>
                                    @if ($job->survay_date != null)
                                        <div>
                                            <label class="form-label"> Survey Date : {{\Carbon\Carbon::parse($job->survay_date)->format('Y-m-d H:i') }} </label>
                                        </div>
                                    @endif
                                    @if ($job->install_date != null)
                                        <div>
                                            <label class="form-label"> Install Date : {{\Carbon\Carbon::parse($job->install_date)->format('Y-m-d H:i') }} </label>
                                        </div>
                                    @endif
                                    @if ($job->service_date != null)
                                        <div>
                                            <label class="form-label"> Service Date : {{\Carbon\Carbon::parse($job->service_date)->format('Y-m-d H:i') }} </label>
                                        </div>
                                    @endif
                                    <div>
                                        <label class="form-label"> Sale : {{ @$sale->name }} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> Engineer : {{@$engineer->name}} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> Team : {{@$team->team_name}} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> ชื่อลูกค้า : {{ @$job->customer_name }} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> เบอร์โทร : {{ @$job->tell }} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> ที่อยู่ : {{@$job->address_customer}} {{@$amphures->name_th}} {{@$provinces->name_th}} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> แผนที่ : <a href="{{$job->google_map}}" target="_blank">{{$job->google_map}}</a></label>
                                    </div>
                                    <div>
                                        <label class="form-label"> รายละเอียดเพิ่มเติม : {{@$job->job_detail}} </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> Start Progress : 
                                            @if ($job->start_progress != null)
                                             {{\Carbon\Carbon::parse($job->start_progress)->format('Y-m-d H:i') }}
                                              @endif
                                        </label>
                                    </div>
                                    <div>
                                        <label class="form-label"> End Progress : 
                                            @if ($job->end_progress != null)
                                             {{\Carbon\Carbon::parse($job->end_progress)->format('Y-m-d H:i') }}
                                              @endif
                                        </label>
                                    </div>
                                    {{-- <div id="map"></div> --}}
                                    <div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
                                        <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 2xl:col-span-2">
                                            <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                                <a href="/album/admin/{{$job->id}}" class="w-3/5 file__icon file__icon--directory mx-auto"></a> <a href="" class="block font-medium mt-4 text-center truncate">Admin</a>
                                            </div>
                                        </div>
                                        <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 2xl:col-span-2">
                                            <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                                <a href="/album/sale/{{$job->id}}" class="w-3/5 file__icon file__icon--directory mx-auto"></a> <a href="" class="block font-medium mt-4 text-center truncate">Sale</a>
                                            </div>
                                        </div>
                                        <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 2xl:col-span-2">
                                            <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                                                <a href="/album/engineer/{{$job->id}}" class="w-3/5 file__icon file__icon--directory mx-auto"></a> <a href="" class="block font-medium mt-4 text-center truncate">Engineer</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            <div id="install-tab" class="tab-pane leading-relaxed p-5 " role="tabpanel" aria-labelledby="install-tab">
                                @if (!empty($head))

                                @foreach ($head as $item)

                                <label class="form-label">{{$item->name}}</label>
                                <div class="mx-6 pb-8">
                                    <div class="responsive-mode">
                                        @foreach ($file_submit as $step1)
                                            @if ($step1->step_file == $item->id )
                                            <div class="max-h-full px-2">
                                                <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md">
                                                    <h3 class="h-full font-medium flex items-center justify-center text-2xl">
                                                        <div class="row">
                                                            <div class="h-set">
                                                                <img  src="{{asset($step1->path."/".$step1->file_name)}}" data-action="zoom"/>
                                                            </div>
                                                            <div class="text-center mt-2">
                                                                <a href="{{ asset($step1->path."/".$step1->file_name) }}" download class="btn btn-sm btn-primary">ดาวน์โหลด</a>
                                                            </div>
                                                        </div>
                                                    </h3>
                                                </div>
                                            </div>
                                            @endif

                                        @endforeach
                                    </div>
                                </div>

                            @endforeach

                            @endif

                                <div>
                                    <label class="form-label">ลายเซ็น ลูกค้า </label>
                                    <div class="mx-6 pb-8">
                                        @php
                                            $signature=DB::table('tb_submit_signature')->where('job_id',$job->id)->first();
                                        @endphp
                                    @if (!empty($signature))
                                    <img style="object-fit: cover;" src="{{asset($signature->path."/".@$signature->signature)}}">
                                    @endif
                                    </div>
                                </div>
                            </div>

                            </div>

                            </div>
                        </div>

                        </div>
                        <!-- END: Tesla report -->
                    </div>
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>


                </div>
            </div>
            <!-- END: Content -->
        </div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            // ดึงข้อมูลละติจูดและลองจิจูดจากตัวแปรที่ส่งมาจาก Laravel
            var lat = {{ $job->google_latitude }};
            var lng = {{ $job->google_longitude }};

            // สร้างแผนที่และตั้งตำแหน่งไปที่ละติจูดและลองจิจูดที่ต้องการ
            var map = L.map('map').setView([lat, lng], 13);

            // กำหนด TileLayer ที่จะใช้ (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // เพิ่ม marker เพื่อแสดงตำแหน่งในแผนที่
            L.marker([lat, lng]).addTo(map)
                .bindPopup('This is your location.<br>Lat: ' + lat + '<br>Lng: ' + lng)
                .openPopup();
        </script>
        @include('backoffice.js.js')

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
