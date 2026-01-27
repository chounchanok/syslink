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
                            <li class="breadcrumb-item"><a href="/backoffice/technician">technician</a></li>
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
                        Add New
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-2">
                    </div>

                    <div class="intro-y col-span-12 lg:col-span-8">
                        <!-- BEGIN: Form Layout -->
                        <form action="{{route('technician.addsub')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="intro-y box p-5">
                            <div>
                                <label for="crud-form-1" class="form-label">Username</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="username" placeholder="username">
                            </div>
                            <div>
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control w-full" required name="password" placeholder="password">
                            </div>
                            <div>
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" class="form-control w-full" required name="password_confirmation" placeholder="confirm password">
                            </div>
                            <div>
                                <label for="crud-form-1" class="form-label">Name</label>
                                <input id="crud-form-1" type="text" class="form-control w-full" required name="name" placeholder="name">
                            </div>
                            @php
                                $team=DB::table('tb_team')->get();
                            @endphp
                            <div>
                                <label for="crud-form-1" class="form-label">Team</label>
                                <select name="team_id" id="" class="form-control">
                                    @foreach ($team as $tm)
                                    <option value="{{$tm->id}}">{{$tm->team_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-right mt-5">
                                <a href="{{url('/technician')}}" class="btn btn-outline-secondary w-24 mr-1">Cancel</a>
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

                confirmPassword.addEventListener('input', function() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity("Passwords do not match");
                    } else {
                        confirmPassword.setCustomValidity("");
                    }
                });
            });
            </script>
    </body>

</html>
