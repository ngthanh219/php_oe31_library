<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ trans('admin.admin') }}</title>
    <link rel="icon" type="image/png" href="assets/dist/img/logo1.png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet"
        href="{{ asset('bower_components/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('bower_components/admin-lte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('bower_components/admin-lte/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('bower_components/admin-lte/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('bower_components/admin-lte/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/dist/css/general.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet"
        href="{{ asset('bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <script src="{{ asset('bower_components/admin-lte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
</head>

<body class="hold-transition skin-blue sidebar-mini" style="font-size: 16px;">
    <div class="wrapper">
        <header class="main-header">
            <a href="" class="logo">
                <span class="logo-mini"><img src="{{ asset('bower_components/admin-lte/dist/img/logo1.png') }}"
                        alt=""></span>
                <span class="logo-lg"><img src="{{ asset('bower_components/admin-lte/dist/img/logo.png') }}"
                        alt=""></span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu" id="user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('bower_components/admin-lte/dist/img/iconUser.png') }}"
                                    class="user-image" alt="User Image">
                                <span class="hidden-xs">
                                    @auth
                                        {{ Auth::user()->name }}
                                    @endauth
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="{{ asset('bower_components/admin-lte/dist/img/iconUser.png') }}"
                                        class="img-circle" alt="User Image">
                                    <p>
                                        @auth
                                            {{ Auth::user()->name }}
                                        @endauth
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href=""
                                            class="btn btn-default btn-flat">{{ trans('user.change_password') }}</a>
                                    </div>
                                    <div class="pull-right">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                dusk="logout"
                                                class="btn btn-default btn-flat">{{ trans('user.log_out') }}</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ asset('bower_components/admin-lte/dist/img/iconUser.png') }}" class="img-circle"
                            alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>
                            @auth
                                {{ Auth::user()->name }}
                            @endauth
                        </p>
                    </div>
                </div>
                <ul class="sidebar-menu" data-widget="tree">
                    @can('admin-role')
                        <li>
                            <a href="{{ route('admin.roles.index') }}">
                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                <span>{{ trans('role.role') }}</span>
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-users"></i>
                            <span>{{ trans('user.users_manager') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.request') }}">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            <span>{{ trans('request.request') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.publishers.index') }}">
                            <i class="fa fa-building"></i>
                            <span>{{ trans('publisher.publisher') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fa fa-tags"></i>
                            <span>{{ trans('category.category') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.authors.index') }}">
                            <i class="fa fa-address-book"></i>
                            <span>{{ trans('author.author') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.books.index') }}">
                            <i class="fa fa-book"></i>
                            <span>{{ trans('book.book') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.book-delete') }}">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                            <span>{{ trans('book.books_manager_deleted') }}</span>
                        </a>
                    </li>
                </ul>
            </section>
        </aside>

        @yield('index')
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>
                    <span id="year"></span> © 2020
                </b>
            </div>
            <strong><a href="">SUN *</a></strong>
        </footer>
        <div class="control-sidebar-bg"></div>
    </div>
    @yield('script')
    <script type="text/javascript" src="{{ asset('/js/user_menu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/sweet-alert.js') }}"></script>
    <script src="{{ asset('bower_components/admin-lte/dist/js/component/general.js') }}">
    </script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}">
    </script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}" defer>
    </script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/select2/dist/js/select2.full.min.js') }}">
    </script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}">
    </script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}">
    </script>
    <script src="{{ asset('bower_components/admin-lte/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('bower_components/admin-lte/dist/js/demo.js') }}"></script>
</body>

</html>
