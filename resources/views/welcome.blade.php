<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container-fluid">
            <div class="min-vh-100 d-flex flex-column align-items-center justify-content-center position-relative">
                <img id="background" class="position-absolute top-0 start-0" style="max-width: 877px;" src="https://laravel.com/assets/img/welcome/background.svg" />
                
                <div class="container position-relative">
                    <header class="row align-items-center py-4">
                        <div class="col-md-4"></div>
                        <div class="col-md-4 text-center">
                            <svg class="h-12 w-auto text-danger" style="height: 4rem;" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253Z" fill="currentColor"/>
                            </svg>
                        </div>
                        @if (Route::has('login'))
                            <div class="col-md-4 text-end">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-dark">Dashboard</a>
                                @else
                                    <a href="{{ route(app()->getLocale().'.login', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-dark me-2">Log in</a>
                                    @if (Route::has('en.register'))
                                        <a href="{{ route(app()->getLocale().'.register', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-dark">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </header>

                    <main class="mt-4">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <a href="https://laravel.com/docs" class="card h-100 text-decoration-none text-dark">
                                    <div class="card-body">
                                        <div class="text-center mb-4">
                                            <img src="https://laravel.com/assets/img/welcome/docs-light.svg" alt="Laravel documentation screenshot" class="img-fluid rounded">
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                                <svg class="text-danger" style="width: 1.5rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M23 4a1 1 0 0 0-1.447-.894L12.224 7.77a.5.5 0 0 1-.448 0L2.447 3.106A1 1 0 0 0 1 4v13.382a1.99 1.99 0 0 0 1.105 1.79l9.448 4.728c.14.065.293.1.447.1.154-.005.306-.04.447-.105l9.453-4.724a1.99 1.99 0 0 0 1.1-1.789V4ZM3 6.023a.25.25 0 0 1 .362-.223l7.5 3.75a.251.251 0 0 1 .138.223v11.2a.25.25 0 0 1-.362.224l-7.5-3.75a.25.25 0 0 1-.138-.22V6.023Zm18 11.2a.25.25 0 0 1-.138.224l-7.5 3.75a.249.249 0 0 1-.329-.099.249.249 0 0 1-.033-.12V9.772a.251.251 0 0 1 .138-.224l7.5-3.75a.25.25 0 0 1 .362.224v11.2Z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="h4">Documentation</h2>
                                                <p class="mb-0">Laravel has wonderful documentation covering every aspect of the framework. Whether you are a newcomer or have prior experience with Laravel, we recommend reading our documentation from beginning to end.</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-6">
                                <a href="https://laracasts.com" class="card h-100 text-decoration-none text-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                                <svg class="text-danger" style="width: 1.5rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <g fill="currentColor">
                                                        <path d="M24 8.25a.5.5 0 0 0-.5-.5H.5a.5.5 0 0 0-.5.5v12a2.5 2.5 0 0 0 2.5 2.5h19a2.5 2.5 0 0 0 2.5-2.5v-12Zm-7.765 5.868a1.221 1.221 0 0 1 0 2.264l-6.626 2.776A1.153 1.153 0 0 1 8 18.123v-5.746a1.151 1.151 0 0 1 1.609-1.035l6.626 2.776Z"/>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="h4">Laracasts</h2>
                                                <p class="mb-0">Laracasts offers thousands of video tutorials on Laravel, PHP, and JavaScript development. Check them out, see for yourself, and massively level up your development skills in the process.</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-6">
                                <a href="https://laravel-news.com" class="card h-100 text-decoration-none text-dark">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                                <svg class="text-danger" style="width: 1.5rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <g fill="currentColor">
                                                        <path d="M8.75 4.5H5.5c-.69 0-1.25.56-1.25 1.25v4.75c0 .69.56 1.25 1.25 1.25h3.25c.69 0 1.25-.56 1.25-1.25V5.75c0-.69-.56-1.25-1.25-1.25Z"/>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="h4">Laravel News</h2>
                                                <p class="mb-0">Laravel News is a community driven portal and newsletter aggregating all of the latest and most important news in the Laravel ecosystem, including new package releases and tutorials.</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                                <svg class="text-danger" style="width: 1.5rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <g fill="currentColor">
                                                        <path d="M16.597 12.635a.247.247 0 0 0-.08-.237 2.234 2.234 0 0 1-.769-1.68c.001-.195.03-.39.084-.578a.25.25 0 0 0-.09-.267 8.8 8.8 0 0 0-4.826-1.66.25.25 0 0 0-.268.181 2.5 2.5 0 0 1-2.4 1.824.045.045 0 0 0-.045.037 12.255 12.255 0 0 0-.093 3.86.251.251 0 0 0 .208.214c2.22.366 4.367 1.08 6.362 2.118a.252.252 0 0 0 .32-.079 10.09 10.09 0 0 0 1.597-3.733Z"/>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="h4">Vibrant Ecosystem</h2>
                                                <p class="mb-0">
                                                    Laravel's robust library of first-party tools and libraries, such as 
                                                    <a href="https://forge.laravel.com" class="text-decoration-underline">Forge</a>,
                                                    <a href="https://vapor.laravel.com" class="text-decoration-underline">Vapor</a>,
                                                    <a href="https://nova.laravel.com" class="text-decoration-underline">Nova</a>,
                                                    <a href="https://envoyer.io" class="text-decoration-underline">Envoyer</a>, and
                                                    <a href="https://herd.laravel.com" class="text-decoration-underline">Herd</a>
                                                    help you take your projects to the next level. Pair them with powerful open source libraries like
                                                    <a href="https://laravel.com/docs/billing" class="text-decoration-underline">Cashier</a>,
                                                    <a href="https://laravel.com/docs/dusk" class="text-decoration-underline">Dusk</a>,
                                                    <a href="https://laravel.com/docs/broadcasting" class="text-decoration-underline">Echo</a>,
                                                    <a href="https://laravel.com/docs/horizon" class="text-decoration-underline">Horizon</a>,
                                                    <a href="https://laravel.com/docs/sanctum" class="text-decoration-underline">Sanctum</a>,
                                                    <a href="https://laravel.com/docs/telescope" class="text-decoration-underline">Telescope</a>, and more.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>

                    <footer class="py-4 text-center text-muted">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </footer>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
