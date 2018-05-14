<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('vendor/admin-lte/img/avatar04.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $user->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active">
                <a href="{{ route('admin') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <!--[@MENU-LINKS@]-->
            <!--DO NOT EDIT/REMOVE THIS! ITS AUTO GENERATED PLACEHOLDER FOR FUTURE MENU LINKS-->

            <li>
                <a href="{{ url('/logout') }}">
                    <i class="fa fa-power-off"></i> <span>Sign out</span>
                </a>
            </li>

            <li class="header">WEBSITE</li>

            <li>
                <a href="{{ url('/') }}">
                    <i class="fa fa-globe"></i> <span>Go back to website</span>
                </a>
            </li>
        </ul>
    </section>
</aside>