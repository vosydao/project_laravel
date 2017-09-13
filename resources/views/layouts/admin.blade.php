<?php $version = 24; ?>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name='robots' content='noindex,follow' />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'Laravel') }} Admin</title>

    <!-- Styles -->
    <link href="{{ URL::asset('/css/app.css') }}?v={{ $version  }}" rel="stylesheet">
    <link href="{{ asset('/public/css/font-awesome.min.css') }}?v={{ $version  }}" rel="stylesheet">
    <link href="{{ asset('/public/css/admin.css') }}?v={{ $version  }}" rel="stylesheet">

    {{--jquery--}}
    <script src="{{ asset('/public/js/jquery.min.js') }}?v={{ $version  }}"></script>
    <script src="{{ asset('/public/js/jquery-ui-1.10.4.custom.min.js') }}"></script>

    <script>
        var site_url = "{{ config('app.url') }}";
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }} Admin
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav admin-bar">
                    <li><a href="{{ config('app.url') }}">View site</a></li>
                    &nbsp;@yield('admin-bar')
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid" style="padding-top: 70px">
        <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-2">
                <div class="panel panel-default">
                    <ul class="list-manage">
                        <li><a href="{{ config('app.url') }}/admin">Dashboard</a></li>
                        <li><a href="{{ config('app.url') }}/admin/setting">Setting</a></li>
                        <li>
                            <a href="javascript:void(0)" class="have-sub" data-show="1">Posts <i class="fa fa-caret-down" aria-hidden="true"></i></a>
                            <ul class="sub">
                                <li><a href="{{ config('app.url') }}/admin/posts"><i class="fa fa-angle-double-right" aria-hidden="true"></i> All posts</a></li>
                                <li><a href="{{ config('app.url') }}/admin/posts/add-new"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Add new post</a></li>
                                <li><a href="{{ config('app.url') }}/admin/categories"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Categories</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ config('app.url') }}/admin/users">Users</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-10">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->

<script src="{{ asset('/public/js/plugins.js') }}?v={{ $version  }}"></script>
<script src="{{ asset('/public/plugins/cropper/cropper.min.js') }}"></script>
<script src="{{ asset('/public/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/public/plugins/ckfinder/ckfinder.js') }}"></script>

<script src="{{ asset('/public/js/admin.js') }}?v={{ $version  }}"></script>
</body>
</html>
