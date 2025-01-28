<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <x-slot name="header">
        <h1 class="h3 mb-0">{{ __('Google Maps Scraper') }}</h1>
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-scraper')

    <div class="content-page py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h4 mb-4">Google Maps Scraper</h3>
                
                <form method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="list_id" class="form-label">{{ __('Lista') }}</label>
                                <select name="list_id" id="list_id" class="form-select" required>
                                    <option value="" selected>{{ __('Seleziona una lista') }}</option>
                                    @foreach($lists as $list)
                                        <option value="{{ $list->id }}">{{ $list->list_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="keyword" class="form-label d-flex align-items-center">
                                    {{ __('Keyword') }}
                                    <span class="ms-2 position-relative d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Campo obbligatorio puoi specificare fino a 700 caratteri nel campo keyword tutti i %## saranno decodificati (anche il carattere "+ sarà decodificato in uno spazio) se hai bisogno di usare il carattere "%" per la tua keyword, specificalo come "%25"; se hai bisogno di usare il carattere "+" per la tua keyword, specificalo come "%2B"; se questo campo contiene parametri come "allinanchor:", "allintext:", "allintitle:", "allinurl:", "define:", "filetype:", "id:", "inanchor:", "info:", "intext:", "intitle:", "inurl:", "link:", "related:", "site:", il costo per task sarà moltiplicato per 5 Nota: le query contenenti il parametro "cache:" non sono supportate e restituiranno un errore di validazione scopri di più sulle regole e limitazioni dei campi keyword e keywords nelle API di DataForSEO in questo articolo del Centro Assistenza') }}">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </label>
                                <input type="text" name="keyword" id="keyword" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="location_coordinate" class="form-label">{{ __('Location Coordinate') }}</label>
                                <input type="text" name="location_coordinate" id="location_coordinate" class="form-control">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="location_code" class="form-label">{{ __('Location') }}</label>
                                <select name="location_code" id="location_code" class="form-select">
                                    <option></option>
                                    @foreach(json_decode(file_get_contents(storage_path('app/json/dfs-google-locations.json')), true) as $location)
                                        <option value="{{ $location['location_code'] }}">{{ $location['location_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#location_code').select2({
                                        placeholder: "Search for a location...",
                                        allowClear: true,
                                        width: '100%',
                                        tags: false
                                    });
                                });
                            </script>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="language_name" class="form-label">{{ __('Language Name') }}</label>
                                <select name="language_name" id="language_name" class="form-select">
                                    <option value="Afrikaans">{{ __('Afrikaans') }}</option>
                                    <option value="Akan">{{ __('Akan') }}</option>
                                    <option value="Albanian">{{ __('Albanese') }}</option>
                                    <option value="Amharic">{{ __('Amarico') }}</option>
                                    <option value="Arabic">{{ __('Arabo') }}</option>
                                    <option value="Armenian">{{ __('Armeno') }}</option>
                                    <option value="Azeri">{{ __('Azero') }}</option>
                                    <option value="Balinese">{{ __('Balinese') }}</option>
                                    <option value="Basque">{{ __('Basco') }}</option>
                                    <option value="Belarusian">{{ __('Bielorusso') }}</option>
                                    <option value="Bengali">{{ __('Bengalese') }}</option>
                                    <option value="Bosnian">{{ __('Bosniaco') }}</option>
                                    <option value="Bulgarian">{{ __('Bulgaro') }}</option>
                                    <option value="Burmese">{{ __('Birmano') }}</option>
                                    <option value="Catalan">{{ __('Catalano') }}</option>
                                    <option value="Cebuano">{{ __('Cebuano') }}</option>
                                    <option value="Chichewa">{{ __('Chichewa') }}</option>
                                    <option value="Chinese (Simplified)">{{ __('Cinese (Semplificato)') }}</option>
                                    <option value="Chinese (Traditional)">{{ __('Cinese (Tradizionale)') }}</option>
                                    <option value="Croatian">{{ __('Croato') }}</option>
                                    <option value="Czech">{{ __('Ceco') }}</option>
                                    <option value="Danish">{{ __('Danese') }}</option>
                                    <option value="Dutch">{{ __('Olandese') }}</option>
                                    <option value="English">{{ __('Inglese') }}</option>
                                    <option value="Espanol (Latinoamerica)">{{ __('Spagnolo (Latinoamerica)') }}</option>
                                    <option value="Estonian">{{ __('Estone') }}</option>
                                    <option value="Ewe">{{ __('Ewe') }}</option>
                                    <option value="Faroese">{{ __('Faroese') }}</option>
                                    <option value="Farsi">{{ __('Farsi') }}</option>
                                    <option value="Filipino">{{ __('Filippino') }}</option>
                                    <option value="Finnish">{{ __('Finlandese') }}</option>
                                    <option value="French">{{ __('Francese') }}</option>
                                    <option value="Frisian">{{ __('Frisone') }}</option>
                                    <option value="Ga">{{ __('Ga') }}</option>
                                    <option value="Galician">{{ __('Galiziano') }}</option>
                                    <option value="Ganda">{{ __('Ganda') }}</option>
                                    <option value="Georgian">{{ __('Georgiano') }}</option>
                                    <option value="German">{{ __('Tedesco') }}</option>
                                    <option value="Greek">{{ __('Greco') }}</option>
                                    <option value="Gujarati">{{ __('Gujarati') }}</option>
                                    <option value="Haitian">{{ __('Haitiano') }}</option>
                                    <option value="Hausa">{{ __('Hausa') }}</option>
                                    <option value="Hebrew">{{ __('Ebraico') }}</option>
                                    <option value="Hebrew (old)">{{ __('Ebraico (vecchio)') }}</option>
                                    <option value="Hindi">{{ __('Hindi') }}</option>
                                    <option value="Hungarian">{{ __('Ungherese') }}</option>
                                    <option value="Icelandic">{{ __('Islandese') }}</option>
                                    <option value="IciBemba">{{ __('IciBemba') }}</option>
                                    <option value="Igbo">{{ __('Igbo') }}</option>
                                    <option value="Indonesian">{{ __('Indonesiano') }}</option>
                                    <option value="Irish">{{ __('Irlandese') }}</option>
                                    <option value="Italian" selected>{{ __('Italiano') }}</option>
                                    <option value="Japanese">{{ __('Giapponese') }}</option>
                                    <option value="Kannada">{{ __('Kannada') }}</option>
                                    <option value="Kazakh">{{ __('Kazako') }}</option>
                                    <option value="Khmer">{{ __('Khmer') }}</option>
                                    <option value="Kinyarwanda">{{ __('Kinyarwanda') }}</option>
                                    <option value="Kirundi">{{ __('Kirundi') }}</option>
                                    <option value="Kongo">{{ __('Kongo') }}</option>
                                    <option value="Korean">{{ __('Coreano') }}</option>
                                    <option value="Kreol morisien">{{ __('Kreol morisien') }}</option>
                                    <option value="Kreol Seselwa">{{ __('Kreol Seselwa') }}</option>
                                    <option value="Krio">{{ __('Krio') }}</option>
                                    <option value="Kurdish">{{ __('Curdo') }}</option>
                                    <option value="Kyrgyz">{{ __('Kirghiso') }}</option>
                                    <option value="Lao">{{ __('Lao') }}</option>
                                    <option value="Latvian">{{ __('Lettone') }}</option>
                                    <option value="Lingala">{{ __('Lingala') }}</option>
                                    <option value="Lithuanian">{{ __('Lituano') }}</option>
                                    <option value="Luo">{{ __('Luo') }}</option>
                                    <option value="Macedonian">{{ __('Macedone') }}</option>
                                    <option value="Malagasy">{{ __('Malagasy') }}</option>
                                    <option value="Malay">{{ __('Malese') }}</option>
                                    <option value="Malayam">{{ __('Malayam') }}</option>
                                    <option value="Maltese">{{ __('Maltese') }}</option>
                                    <option value="Maori">{{ __('Maori') }}</option>
                                    <option value="Marathi">{{ __('Marathi') }}</option>
                                    <option value="Mongolian">{{ __('Mongolo') }}</option>
                                    <option value="Montenegro">{{ __('Montenegrino') }}</option>
                                    <option value="Nepali">{{ __('Nepalese') }}</option>
                                    <option value="Northern Sotho">{{ __('Sotho del Nord') }}</option>
                                    <option value="Norwegian">{{ __('Norvegese') }}</option>
                                    <option value="Nyankole">{{ __('Nyankole') }}</option>
                                    <option value="Oromo">{{ __('Oromo') }}</option>
                                    <option value="Pashto">{{ __('Pashto') }}</option>
                                    <option value="Pidgin">{{ __('Pidgin') }}</option>
                                    <option value="Polish">{{ __('Polacco') }}</option>
                                    <option value="Portuguese">{{ __('Portoghese') }}</option>
                                    <option value="Portuguese (Brazil)">{{ __('Portoghese (Brasile)') }}</option>
                                    <option value="Portuguese (Portugal)">{{ __('Portoghese (Portogallo)') }}</option>
                                    <option value="Punjabi">{{ __('Punjabi') }}</option>
                                    <option value="Quechua">{{ __('Quechua') }}</option>
                                    <option value="Romanian">{{ __('Rumeno') }}</option>
                                    <option value="Romansh">{{ __('Romancio') }}</option>
                                    <option value="Russian">{{ __('Russo') }}</option>
                                    <option value="Serbian">{{ __('Serbo') }}</option>
                                    <option value="Serbian (Latin)">{{ __('Serbo (Latino)') }}</option>
                                    <option value="Sesotho">{{ __('Sesotho') }}</option>
                                    <option value="Shona">{{ __('Shona') }}</option>
                                    <option value="Silozi">{{ __('Silozi') }}</option>
                                    <option value="Sindhi">{{ __('Sindhi') }}</option>
                                    <option value="Sinhalese">{{ __('Singalese') }}</option>
                                    <option value="Slovak">{{ __('Slovacco') }}</option>
                                    <option value="Slovenian">{{ __('Sloveno') }}</option>
                                    <option value="Somali">{{ __('Somalo') }}</option>
                                    <option value="Spanish">{{ __('Spagnolo') }}</option>
                                    <option value="Swahili">{{ __('Swahili') }}</option>
                                    <option value="Swedish">{{ __('Svedese') }}</option>
                                    <option value="Tagalog">{{ __('Tagalog') }}</option>
                                    <option value="Tajik">{{ __('Tagico') }}</option>
                                    <option value="Tamil">{{ __('Tamil') }}</option>
                                    <option value="Telugu">{{ __('Telugu') }}</option>
                                    <option value="Thai">{{ __('Tailandese') }}</option>
                                    <option value="Tigrinya">{{ __('Tigrino') }}</option>
                                    <option value="Tonga (Tonga Islands)">{{ __('Tonga (Isole Tonga)') }}</option>
                                    <option value="Tshiluba">{{ __('Tshiluba') }}</option>
                                    <option value="Tswana">{{ __('Tswana') }}</option>
                                    <option value="Tumbuka">{{ __('Tumbuka') }}</option>
                                    <option value="Turkish">{{ __('Turco') }}</option>
                                    <option value="Turkmen">{{ __('Turkmeno') }}</option>
                                    <option value="Ukrainian">{{ __('Ucraino') }}</option>
                                    <option value="Urdu">{{ __('Urdu') }}</option>
                                    <option value="Uzbek">{{ __('Uzbeko') }}</option>
                                    <option value="Vietnamese">{{ __('Vietnamita') }}</option>
                                    <option value="Wolof">{{ __('Wolof') }}</option>
                                    <option value="Xhosa">{{ __('Xhosa') }}</option>
                                    <option value="Yoruba">{{ __('Yoruba') }}</option>
                                    <option value="Zulu">{{ __('Zulu') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="priority" class="form-label d-flex align-items-center">
                                    {{ __('Priority') }}
                                    <span class="ms-2 position-relative d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Campo opzionale può assumere i seguenti valori: 1 – priorità di esecuzione normale (impostata di default) 2 – priorità di esecuzione alta') }}">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </label>
                                <select name="priority" id="priority" class="form-select">
                                    <option value="1">{{ __('Normal (up to 5 minutes)') }}</option>
                                    <option value="2">{{ __('High (up to 1 minutes)') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="depth" class="form-label">{{ __('Numero di Risultati') }}</label>
                                <select name="depth" id="depth" class="form-select">
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                    <option value="300">300</option>
                                    <option value="400">400</option>
                                    <option value="500">500</option>
                                    <option value="600">600</option>
                                    <option value="700">700</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label d-flex align-items-center">
                                    {{ __('Ricerche in questa area') }}
                                    <span class="ms-2 position-relative d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('è un campo facoltativo può assumere i valori: true, false valore predefinito: true se impostato su false, la search_this_area modalità verrà disattivata Nota: se la search_this_area modalità è disattivata, gli elenchi di Google Maps potrebbero contenere risultati oltre l\'area visualizzata') }}">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </label>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="search_this_area" id="search_this_area" class="form-check-input" checked>
                                    <label class="form-check-label" for="search_this_area">{{ __('Abilita ricerca in questa area') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="form-label d-flex align-items-center">
                                    {{ __('Ricerca luoghi') }}
                                    <span class="ms-2 position-relative d-inline-block" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('la modalità di ricerca luoghi consente di ottenere risultati di Google Maps su un determinato luogo (ad esempio, Apple Store a New York) tuttavia, a causa delle peculiarità del nostro algoritmo di data mining, questa modalità potrebbe interferire con alcune query con intento locale e visualizzare risultati per una posizione diversa da quella specificata nella richiesta; per evitare questa interferenza e ottenere risultati corretti per le parole chiave con intento locale, è possibile impostare questo parametro su false;valore predefinito: true Nota: se la search_places modalità è disattivata e non sono stati trovati risultati nell\'area di ricerca, l\' results array sarà vuoto') }}">
                                        <i class="fas fa-question-circle"></i>
                                    </span>
                                </label>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="search_places" id="search_places" class="form-check-input" checked>
                                    <label class="form-check-label" for="search_places">{{ __('Abilita ricerca luoghi') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>