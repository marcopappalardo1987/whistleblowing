<x-app-layout>

    <x-slot name="header">
        {{ __('Aggiungi alla lista') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-scraper-lists')

    <div class="card mt-4">
        <div class="card-body">
            <h3 class="h4 mb-4">{{ __('Dati azienda')}}</h3>
            <form method="POST" action="{{ route('scraper.list.content.view.store', ['list_id' => $list->getAttributes()['id']]) }}">
                @csrf
                <div class="container">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="company" class="form-label">
                                    {{ __('Nome dell\'azienda') }}
                                </label>
                                <input type="text" name="company" id="company" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="domain" class="form-label">
                                    {{ __('Sito Web') }}
                                </label>
                                <input type="text" name="domain" id="domain" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="borough" class="form-label">
                                    {{ __('Comune') }}
                                </label>
                                <input type="text" name="borough" id="borough" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="address" class="form-label">
                                    {{ __('Indirizzo') }}
                                </label>
                                <input type="text" name="address" id="address" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="city" class="form-label">
                                    {{ __('Città') }}
                                </label>
                                <input type="text" name="city" id="city" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="post_code" class="form-label">
                                    {{ __('Codice Postale') }}
                                </label>
                                <input type="text" name="post_code" id="post_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="region" class="form-label">
                                    {{ __('Regione') }}
                                </label>
                                <input type="text" name="region" id="region" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="country_code" class="form-label">
                                    {{ __('Codice Paese') }}
                                </label>
                                <input type="text" name="country_code" id="country_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    {{ __('Email') }}
                                </label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    {{ __('Telefono') }}
                                </label>
                                <input type="text" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="form-group">
                                <label for="categories" class="form-label">
                                    {{ __('Categorie') }}
                                </label>
                                <input type="text" name="categories" id="categories" class="form-control">
                            </div>
                        </div>
                        <input type="hidden" name="scrape_list_id" value="{{ $list->id }}">
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