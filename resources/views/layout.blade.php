<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('title')</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <ul class="nav nav-tabs">
        <li role="presentation"{!! app()->getCurrentRoute() === 'main' ? ' class="active"' : '' !!}><a href="/">Парсер</a></li>
        <li role="presentation"{!! app()->getCurrentRoute() === 'categories' ? ' class="active"' : '' !!}><a href="/categories">Категории</a></li>
        <li role="presentation"{!! app()->getCurrentRoute() === 'pages' ? ' class="active"' : '' !!}><a href="/pages">Страницы</a></li>
        <li role="presentation"{!! app()->getCurrentRoute() === 'products' ? ' class="active"' : '' !!}><a href="/products">Товары</a></li>
    </ul>

    <div class="container text-center">
        @yield('content')
    </div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>