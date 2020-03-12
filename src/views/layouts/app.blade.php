<!DOCTYPE html>
<html lang="zh-CN" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/wangEditor/10.0.13/wangEditor.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/logo.png">

    <style>
        html,
        body {
            overflow-x: hidden; /* Prevent scroll on narrow devices */
        }

        body {
            padding-top: 56px;
        }

        @media (max-width: 991.98px) {
            .offcanvas-collapse {
                position: fixed;
                top: 56px; /* Height of navbar */
                bottom: 0;
                left: 100%;
                width: 100%;
                padding-right: 1rem;
                padding-left: 1rem;
                overflow-y: auto;
                visibility: hidden;
                background-color: #343a40;
                transition: visibility .3s ease-in-out, -webkit-transform .3s ease-in-out;
                transition: transform .3s ease-in-out, visibility .3s ease-in-out;
                transition: transform .3s ease-in-out, visibility .3s ease-in-out, -webkit-transform .3s ease-in-out;
            }
            .offcanvas-collapse.open {
                visibility: visible;
                -webkit-transform: translateX(-100%);
                transform: translateX(-100%);
            }
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            color: rgba(255, 255, 255, .75);
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .nav-underline .nav-link {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: .875rem;
            color: #6c757d;
        }

        .nav-underline .nav-link:hover {
            color: #007bff;
        }

        .nav-underline .active {
            font-weight: 500;
            color: #343a40;
        }

        .text-white-50 { color: rgba(255, 255, 255, .5); }

        .bg-purple { background-color: #6f42c1; }

        .lh-100 { line-height: 1; }
        .lh-125 { line-height: 1.25; }
        .lh-150 { line-height: 1.5; }

        .footer {
            background-color: #f5f5f5;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
            font-size: 3.5rem;
            }
        }

        .dn, .mobile {
            display: none;
        }

        .content {
            height: 300px !important;
            border-radius: 0px;
            border-top: 0px;
        }

        .w-e-menu a {
            color: #999;
            text-decoration: none;
            font-weight: 600;
        }
        .w-e-menu a:hover {
            color: #333;
        }
    </style>

    <title>{{ config('app.name') }} - @yield('title')</title>
</head>
<body class="bg-light d-flex flex-column h-100">
    @include('admini::layouts.header')

    <main role="main" class="container flex-shrink-0">
        @include('admini::layouts.title')
        @include('admini::layouts.alert')
        @yield('content')
    </main>

    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} {Admini} by 0xSmart</span>
        </div>
    </footer>

    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/wangEditor/10.0.13/wangEditor.min.js"></script>

    @stack('scripts')
</body>
</html>
