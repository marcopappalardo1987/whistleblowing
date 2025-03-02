<footer class="text-center mt-4 py-3">
    <div class="container">
        <div class="footer p-4 rounded-4">
            <div class="row align-items-center">
                <div class="col-lg-3 text-lg-start text-center">
                    <a class="navbar-brand" href="https://www.whistleblowingtool.com/{{ app()->getLocale() }}">
                        <img class="logo" src="https://whistleblowingtool.com/images/whistleblowingtool-logo.webp" alt="{{ __('Whistleblowing Tool Logo') }}">
                    </a>
                </div>
                <div class="col-lg-6">
                    <nav class="navbar">
                        <ul class="navbar-nav flex-column flex-lg-row justify-content-lg-center w-100">
                            <li class="nav-item text-center px-2">
                                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.plans', ['locale' => app()->getLocale()]) }}">{{ __('Piani') }}</a>
                            </li>
                            <li class="nav-item text-center px-2">
                                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.features', ['locale' => app()->getLocale()]) }}">{{ __('Funzionalità') }}</a>
                            </li>
                            <li class="nav-item text-center px-2">
                                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.blog', ['locale' => app()->getLocale()]) }}">{{ __('Da sapere') }}</a>
                            </li>
                            <li class="nav-item text-center px-2">
                                <a class="nav-link p-0" href="{{ route(app()->getLocale().'.frontend.assistance', ['locale' => app()->getLocale()]) }}">{{ __('Assistenza') }}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 text-lg-end text-center">
                    <a class="social-link mx-1" href="{{ route('social.linkedin') }}"><img src="https://whistleblowingtool.com/images/linkedin.svg" alt="{{ __('Linkedin') }}"></a>
                    <a class="social-link mx-1" href="{{ route('social.instagram') }}"><img src="https://whistleblowingtool.com/images/instagram.svg" alt="{{ __('Instagram') }}"></a>
                    <a class="social-link mx-1" href="{{ route('social.facebook') }}"><img src="https://whistleblowingtool.com/images/facebook.svg" alt="{{ __('Facebook') }}"></a>
                </div>
                <div class="col-12 mt-3">
                    <div class="d-flex justify-content-center">
                        <a class="btn btn-border-secondary" href="{{ route(app()->getLocale().'.frontend.privacy-policy', ['locale' => app()->getLocale()]) }}">{{ __('Privacy Policy') }}</a>
                        <a class="btn btn-border-secondary" href="{{ route(app()->getLocale().'.frontend.cookie-policy', ['locale' => app()->getLocale()]) }}">{{ __('Cookie Policy') }}</a>
                    </div>
                    <p class="m-0 copyright color1">&copy; {{ date('Y') }} - {{ __('Tutti i diritti riservati WhistleblowingTool') }}</p>
                </div>
            </div>
        </div>
    </div>
</footer>