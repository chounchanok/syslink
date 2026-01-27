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
                            <li class="breadcrumb-item"><a href="/backoffice/job">Job</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ส่งงาน Service</li>
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
                        Service
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>

                    <div class="intro-y col-span-12 lg:col-span-8">
                        <!-- BEGIN: Tesla report -->
                            @csrf
                        <div class="intro-y box p-5">
                            <div>
                                <label class="form-label">รูปก่อนแก้ไข </label>
                                <div class="mx-6 pb-8">
                                    <div class="responsive-mode">
                                        @foreach ($file_job as $step1)
                                            @if ($step1->step_file == 1)
                                            <div class="max-h-full px-2">
                                                <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md">
                                                    <h3 class="h-full font-medium flex items-center justify-center text-2xl">
                                                        <div class="row">
                                                            <div class="h-set">
                                                                <img style="object-fit: cover;" src="{{asset("$step1->path/$step1->file_name")}}" data-action="zoom"/>
                                                            </div>
                                                            <div class="text-center mt-2">
                                                                <a href="{{ asset("$step1->path/$step1->file_name") }}" download class="btn btn-sm btn-primary">ดาวน์โหลด</a>
                                                            </div>
                                                        </div>
                                                    </h3>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">รูปหลังแก้ไข </label>
                                <div class="mx-6 pb-8">
                                    <div class="responsive-mode">
                                        @foreach ($file_job as $step2)
                                            @if ($step2->step_file == 2)
                                            <div class="max-h-full px-2">
                                                <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md">
                                                    <h3 class="h-full font-medium flex items-center justify-center text-2xl">
                                                        <div class="row">
                                                            <div class="h-set">
                                                                <img style="object-fit: cover;" src="{{asset("$step2->path/$step2->file_name")}}" data-action="zoom"/>
                                                            </div>
                                                            <div class="text-center mt-2">
                                                                <a href="{{ asset("$step2->path/$step2->file_name") }}" download class="btn btn-sm btn-primary">ดาวน์โหลด</a>
                                                            </div>
                                                        </div>
                                                    </h3>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">หมายเหตุ </label>
                                <div class="w-full h-64 my-5 image-fit">
                                    <textarea name="" class="form-control" id="" cols="30" rows="10"></textarea>
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
