<x-app-layout>

    <x-slot name="header">
        {{ __('Modifica contenuto lista') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-scraper-lists')

    <div class="card mt-4">
        <div class="card-body">
            <h3 class="h4 mb-4">{{ __('Dati azienda')}}</h3>
            <form method="POST" action="{{ route('scraper.list.content.view.store', ['list_id' => $content->scrape_list_id, 'content_id' => $content->id]) }}">
                @csrf
                <div class="container">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="company" class="form-label">
                                    {{ __('Nome dell\'azienda') }}
                                </label>
                                <input type="text" name="company" id="company" value="{{ $content->company }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="domain" class="form-label">
                                    {{ __('Sito Web') }}
                                </label>
                                <input type="text" name="domain" id="domain" value="{{ $content->domain }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="borough" class="form-label">
                                    {{ __('Comune') }}
                                </label>
                                <input type="text" name="borough" id="borough" value="{{ $content->borough }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="address" class="form-label">
                                    {{ __('Indirizzo') }}
                                </label>
                                <input type="text" name="address" id="address" value="{{ $content->address }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="city" class="form-label">
                                    {{ __('Città') }}
                                </label>
                                <input type="text" name="city" id="city" value="{{ $content->city }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="post_code" class="form-label">
                                    {{ __('Codice Postale') }}
                                </label>
                                <input type="text" name="post_code" id="post_code" value="{{ $content->post_code }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="region" class="form-label">
                                    {{ __('Regione') }}
                                </label>
                                <input type="text" name="region" id="region" value="{{ $content->region }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="country_code" class="form-label">
                                    {{ __('Codice Paese') }}
                                </label>
                                <input type="text" name="country_code" id="country_code" value="{{ $content->country_code }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    {{ __('Email') }}
                                </label>
                                <input type="text" name="email" id="email" value="{{ is_array($content->email) ? implode(', ', $content->email) : $content->email }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    {{ __('Telefono') }}
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ $content->phone }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="categories" class="form-label">
                                    {{ __('Categorie') }}
                                </label>
                                <input type="text" name="categories" id="categories" value="{{ $content->categories }}" class="form-control">
                            </div>
                        </div>
                        <input type="hidden" name="scrape_list_id" value="{{ $content->scrape_list_id }}">
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-dark">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>