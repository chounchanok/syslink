<nav class="side-nav">
    <p href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Midone - HTML Admin Template" class="w-6" src="{{asset('dist/images/logo.svg')}}">
        <span class="hidden xl:block text-white text-lg ml-3">  </span>
    </p>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="{{url('/')}}" class="side-menu {{request()->is('/') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>
        <li>
            <a href="{{url('/calendar')}}" class="side-menu {{request()->is('calendar') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div>
                <div class="side-menu__title"> Calendar </div>
            </a>
        </li>

        @if (auth()->guard('admin')->user()->role == "superadmin" || auth()->guard('admin')->user()->role == "admin")
        <li>
            <a href="{{url('/admin')}}" class="side-menu {{request()->is('admin') || request()->is('admin/*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
                <div class="side-menu__title"> Admin </div>
            </a>
        </li>
        @endif

        {{-- User Management --}}
        <li>
            <a href="javascript:;" class="side-menu {{request()->is('technician*') || request()->is('engineer*') || request()->is('sale*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="users"></i>  </div>
                <div class="side-menu__title">
                    Users
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{request()->is('technician*') || request()->is('engineer*') || request()->is('sale*') ? 'side-menu__sub-open' : ''}}">
                <li>
                    <a href="{{url('/technician')}}" class="side-menu {{request()->is('technician*') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="wrench"></i> </div>
                        <div class="side-menu__title"> Technician </div>
                    </a>
                </li>
                <li>
                    <a href="{{url('/engineer')}}" class="side-menu {{request()->is('engineer*') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="hard-hat"></i> </div>
                        <div class="side-menu__title"> Engineer </div>
                    </a>
                </li>
                <li>
                    <a href="{{url('/sale')}}" class="side-menu {{request()->is('sale*') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                        <div class="side-menu__title"> Sale </div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="side-nav__devider my-6"></li>

        <li>
            <a href="{{url('/team')}}" class="side-menu {{request()->is('team') || request()->is('team/*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                <div class="side-menu__title"> Team </div>
            </a>
        </li>

        {{-- Project Management (รวม Site Management) --}}
        <li>
            <a href="javascript:;" class="side-menu {{request()->is('project') || request()->is('project/*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"><i data-lucide="layout"></i> </div>
                <div class="side-menu__title">
                    Project
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{request()->is('project') || request()->is('project/*') ? 'side-menu__sub-open' : ''}}">
                <li>
                    <a href="{{route('project')}}" class="side-menu {{request()->is('project') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title"> All Projects </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('project.sites')}}" class="side-menu {{request()->is('project/sites') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="map-pin"></i> </div>
                        <div class="side-menu__title"> Site Management </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('project.assets')}}" class="side-menu {{request()->is('project/assets') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                        <div class="side-menu__title"> Asset Tracking </div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Job Management (รวม Templates & Approval) --}}
        <li>
            <a href="javascript:;" class="side-menu {{request()->is('job') || request()->is('job/*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="clipboard-list"></i> </div>
                <div class="side-menu__title">
                    Job
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{request()->is('job') || request()->is('job/*') ? 'side-menu__sub-open' : ''}}">
                <li>
                    <a href="{{url('/job')}}" class="side-menu {{request()->is('job') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> All Jobs </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('job.templates')}}" class="side-menu {{request()->is('job/templates') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="copy"></i> </div>
                        <div class="side-menu__title"> Job Templates </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('job.approvals')}}" class="side-menu {{request()->is('job/approvals') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="check-circle"></i> </div>
                        <div class="side-menu__title"> Approvals </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('job.logs')}}" class="side-menu {{request()->is('job/logs') ? 'side-menu--active' : ''}}">
                        <div class="side-menu__icon"> <i data-lucide="clock"></i> </div>
                        <div class="side-menu__title"> Check-in Logs </div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{url('/product')}}" class="side-menu {{request()->is('product') || request()->is('product/*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="package"></i> </div>
                <div class="side-menu__title"> Product (Inventory) </div>
            </a>
        </li>

        <li class="side-nav__devider my-6"></li>

        {{-- New Menus --}}
        <li>
            <a href="{{route('documents')}}" class="side-menu {{request()->is('documents*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                <div class="side-menu__title"> Documents </div>
            </a>
        </li>
        <li>
            <a href="{{route('reports')}}" class="side-menu {{request()->is('reports*') ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="bar-chart-2"></i> </div>
                <div class="side-menu__title"> Reports </div>
            </a>
        </li>

    </ul>
</nav>
