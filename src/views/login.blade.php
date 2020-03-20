<!DOCTYPE html>
<html lang="zh-CN">
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
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .checkbox {
            font-weight: 400;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
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
    </style>

    <title>{{ config('app.name') }} - {{ trans('admini::auth.login') }}</title>
</head>
<body class="text-center">
    <form class="form-signin"action="{{ route('admini.auth.auth') }}" method="post">
        <img class="mb-4" src="https://iph.href.lu/72x72?text={Admini}&bg=4ab1e2&fb=f1a490" alt="" width="72" height="72">

        <h1 class="h3 mb-3 font-weight-normal">{{ trans('admini::auth.login_title') }}</h1>
        <label for="inputEmail" class="sr-only">{{ trans('admini::auth.email') }}</label>
        <input type="email" id="inputEmail" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="{{ trans('admini::auth.email_address') }}" required autofocus>
        @if ($errors->has('email'))
        <div class="invalid-feedback">
            {{ $errors->first('email') }}
        </div>
        @endif
        <label for="inputPassword" class="sr-only">{{ trans('admini::auth.password') }}</label>
        <input type="password" id="inputPassword" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ trans('admini::auth.password') }}" required>
        @if ($errors->has('password'))
        <div class="invalid-feedback">
            {{ $errors->first('password') }}
        </div>
        @endif
        @csrf
        <button class="btn btn-lg btn-primary btn-block" type="submit">{{ trans('admini::auth.login') }}</button>
        <p class="mt-5 mb-3 text-muted">&copy; {{ date('Y') }} {Admini} by 0xSmart</p>
    </form>
</body>
</html>
