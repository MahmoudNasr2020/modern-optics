<style>
    @media (max-width: 991px){

        .header {
            left: 0 !important;
        }

        .left-side {
            transform: translateX(-100%);
        }

        body.sidebar-open .left-side {
            transform: translateX(0);
        }
    }

</style>
<!-- header logo: style can be found in header.less -->
<header class="header">
           {{-- <a href="{{route('dashboard.index')}}" class="logo" style="margin-bottom: 20px">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img src="{{asset('assets/img/modern.png')}}" width="190px" height="105px" alt="">
            </a>--}}
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{ auth()->user()->first_name . ' ' . auth()->user()->last_name  }} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="{{url('/storage/uploads/images/users/') .'/' . auth()->user()->image}}" class="img-circle" alt="User Image" />
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('dashboard.logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
