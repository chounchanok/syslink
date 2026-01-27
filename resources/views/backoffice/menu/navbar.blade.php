<nav class="side-nav">
    <p href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Midone - HTML Admin Template" class="w-6" src="{{asset('dist/images/logo.svg')}}">
        <span class="hidden xl:block text-white text-lg ml-3">  </span>
    </p>
    <div class="side-nav__devider my-6"></div>
    <ul>
    <li>
        {{-- <a href="#" class="side-menu {{request()->is('backoffice/order') || request()->is('/backoffice/order/view/') ? 'side-menu--active' : ''}}"> --}}
        <a href="{{url('/')}}" class="side-menu {{request()->is('/') ? 'side-menu--active' : ''}}">
            <div class="side-menu__icon"> <i data-lucide="Home"></i> </div>
            <div class="side-menu__title"> Dashboard </div>
        </a>
    </li>

    {{-- @if (auth()->user()->role == 'MD' || auth()->user()->role == 'inspector') --}}
    @if (auth()->guard('admin')->user()->role == "superadmin" || auth()->guard('admin')->user()->role == "admin")

    <li>
        {{-- <a href="#" class="side-menu {{request()->is('backoffice/order') || request()->is('/backoffice/order/view/') ? 'side-menu--active' : ''}}"> --}}
        <a href="{{url('/admin')}}" class="side-menu {{request()->is('admin') || request()->is('admin/add') || request()->is('admin/edit') ? 'side-menu--active' : ''}}">
            <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
            <div class="side-menu__title"> Admin </div>
        </a>
    </li>
    @endif
    <li>
        <a href="javascript:;" class="side-menu {{request()->is('technician') || request()->is('engineer') || request()->is('sale') ||request()->is('technician/*') || request()->is('engineer/*') ||request()->is('sale/*')?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="folder"></i>  </div>
            <div class="side-menu__title">
                User
                <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class=" {{request()->is('technician') || request()->is('engineer') ||request()->is('sale') ||request()->is('technician/*') || request()->is('engineer/*') ||request()->is('sale/*')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{url('/technician')}}" class="side-menu {{request()->is('technician')||request()->is('technician/*')?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                    <div class="side-menu__title"> Technician </div>
                </a>
            </li>
            <li>
                <a href="{{url('/engineer')}}" class="side-menu {{request()->is('engineer')||request()->is('engineer/*')?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                    <div class="side-menu__title"> Engineer </div>
                </a>
            </li>
            <li>
                <a href="{{url('/sale')}}" class="side-menu {{request()->is('sale')||request()->is('sale/*')?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
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
    <li>
        <a href="{{route('project')}}" class="side-menu {{request()->is('project') || request()->is('project/*') ? 'side-menu--active' : ''}}">
            <div class="side-menu__icon"><i data-lucide="file-text"></i> </div>
            <div class="side-menu__title"> Project </div>
        </a>
    </li>
    <li>
        <a href="{{url('/job')}}" class="side-menu {{request()->is('job') || request()->is('job/*') ? 'side-menu--active' : ''}}">
            <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
            <div class="side-menu__title"> Job </div>
        </a>
    </li>
    <li>
        <a href="{{url('/product')}}" class="side-menu {{request()->is('product') || request()->is('product/*') ? 'side-menu--active' : ''}}">
            <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
            <div class="side-menu__title"> Product </div>
        </a>
    </li>



    </ul>
</nav>
