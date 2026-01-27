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
                            <li class="breadcrumb-item"><a href="/backoffice/user">Sale</a></li>
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
                        Edit New User
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>

                    <div class="intro-y col-span-12 lg:col-span-8">
                        <!-- BEGIN: Form Layout -->
                        <form action="{{url('/sale/edit/sub')}}" method="POST"id="saveForm" enctype="multipart/form-data">
                            @csrf
                        <div class="intro-y box p-5">
                            <input type="hidden" name="id" value="{{$sale->id}}" id="">


                            <div>
                                <label for="crud-form-1" class="form-label">Username</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="username" placeholder="Input text" value="{{$sale->username}}">
                            </div>
                            <div>
                                <label for="password" class="form-label">Password <span style="color: red">* (If you don't want to change your password, you don't need to enter it.)</span></label>
                                <input id="password" type="password" class="form-control w-full" name="password" placeholder="Input text" value="">
                            </div>
                            <div>
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" class="form-control w-full" required name="password_confirmation" placeholder="confirm password">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Name</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="name" value="{{$sale->name}}" placeholder="name">
                            </div>

                            <div class="text-right mt-5">
                                <a href="{{url('/sale')}}" class="btn btn-outline-secondary w-24 mr-1">Cancel</a>
                                <button type="submit" id="saveButton" class="btn btn-primary w-24">Save</button>
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
        <div class="text-center" style="display: none"> <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#button-modal-preview" class="btn btn-primary" id="auto-click-button">Show Modal</a> </div> <!-- END: Modal Toggle -->
        <!-- BEGIN: Modal Content -->
        <div id="button-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i> </a>
                    <div class="modal-body p-0">
                        <div class="p-5 text-center"> <i data-lucide="check-circle" class="w-16 h-16 text-success mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Success</div>
                            <div class="text-slate-500 mt-2">บันทึกข้อมูลเรียบร้อย</div>
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
        <!-- BEGIN: Dark Mode Switcher-->
        {{-- <div data-url="side-menu-dark-crud-form.html" class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-slate-600 dark:text-slate-200">Dark Mode</div>
            <div class="dark-mode-switcher__toggle border"></div>
        </div> --}}
        <!-- END: Dark Mode Switcher-->

        <!-- BEGIN: JS Assets-->
        {{-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
        <script src="dist/js/ckeditor-classic.js"></script> --}}
        @include('backoffice.js.js')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('password_confirmation');
                const saveButton = document.getElementById('saveButton');
            
                confirmPassword.addEventListener('input', function() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity("Passwords do not match");
                    } else {
                        confirmPassword.setCustomValidity("");
                    }
                });
            
                saveButton.addEventListener('click', function(event) {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity("Passwords do not match");
                        confirmPassword.reportValidity();
                    } else {
                        confirmPassword.setCustomValidity("");
                        event.preventDefault();
            
                        Swal.fire({
                            title: 'คุณต้องการบันทึกหรือไม่?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#28a745', // เขียว
                            cancelButtonColor: '#dc3545',  // แดง
                            confirmButtonText: 'บันทึก',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('saveForm').submit();
                            }
                        });
                    }
                });
            });
            </script>
    </body>

</html>
