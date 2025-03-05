<x-app-layout>
    
    <x-slot name="header">
        <div class="container"> 
            {{ __('Dettagli Segnalazione') }}<br>
            <h6>{{ __('id: ' . $report->id) }}</h6>
        </div>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('layouts.alert-message')  
            </div>
        </div>
    </div>

    <div class="container">
        @foreach($report->forms as $form)
                @php
                    if($form->writer == 'whistleblower') {
                        $class = 'message background-color6 whistleblower-message';
                        $alignColumn = '';
                    } else {
                        $class = 'message background-color1 investigator-message text-white';
                        $alignColumn = 'justify-content-end text-end';
                    }
                @endphp
                <div class="row {{ $alignColumn }}">
                    <div class="col-md-10 mb-5">
                        <div class="{{ $class }} p-4">
                            <div class="message-writer">
                                <span class="badge background-color3">{{ $form->writer != 'whistleblower' ? __('investigatore') : __('whistleblower') }}</span>
                            </div>
                            <div class="message-content py-3">
                                @foreach($form->metadata as $metadata)
                                    <div class="metadata-item mb-2">
                                        <strong>{{ decrypt($metadata->meta_key) }}:</strong><br>
                                        @php
                                            $metaValue = decrypt($metadata->meta_value);
                                        @endphp
                                        <span>
                                            @if (str_contains($metaValue, 'storage/reports'))
                                                <a href="{{ $metaValue }}" class="btn btn-sm btn-primary mt-1">{{ __('Apri allegato') }}</a>  
                                            @else
                                                <span>{{ $metaValue }}</span>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="date-message ">
                                <span class="small">{{ $form->created_at }}</span>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
        <div class="row">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ __('Rispondi alla segnalazione') }}</h5>
                        <form method="POST" action="{{ route('investigator.report.reply', ['id' => $report->id]) }}">
                            @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label">{{ __('Il tuo messaggio') }}</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Invia risposta') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

     
</x-app-layout>
