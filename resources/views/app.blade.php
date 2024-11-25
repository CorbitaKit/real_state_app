<!DOCTYPE html>
<html class="h-full bg-white">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&amp;display=swap" rel="stylesheet">
    @include('assets.styles')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body class="h-full">
    <div class="app">
        <div class="app-wrap">
            <div class="loader">
                <div class="h-100 d-flex justify-content-center">
                    <div class="align-self-center">
                        <img src="/assets/img/loader/loader.svg" alt="loader">
                    </div>
                </div>
            </div>
            @inertia
        </div>
    </div>
    @include('assets.scripts')
</body>
    
</html>