<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="{{route('home')}}">{{config('app.name', 'Analyzer') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{route('home')}}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('index')}}">Domains</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="class=position-relative allert-container">
            @include('flash::message')
        </div>
    </header>
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    
    @yield('content')
    <footer class="border-top py-3 mt-5">
        <div class="container-lg">
            <div class="text-center">
                created by
                <a href="https://github.com/Liocha" target="_blank">Liocha</a>
            </div>
        </div>
    </footer>
</body>
<script src="//code.jquery.com/jquery.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    let wWidth = $('body').width();
    $('div.alert').css("position", "absolute").outerWidth(wWidth).delay(6000).fadeOut();
</script>
</html>