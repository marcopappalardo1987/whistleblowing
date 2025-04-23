<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="max-width: 600px; margin: 0 auto; padding: 20px; color: #333;">

    <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
    <h1 style="color: #2d3748;">{{__('👋 Ci dispiace vederti andare')}}</h1>
    
    <p>{{__('Gentile')}} {{ $data['user_name'] }},</p>
    
    <p style="margin-bottom: 20px;">{{__('Abbiamo ricevuto la tua richiesta di cancellazione dell\'abbonamento. Ci dispiace molto che tu abbia deciso di lasciarci.')}}</p>
    
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; border: 1px solid #dee2e6; margin: 20px 0;">
        <h2 style="color: #00796b; margin-top: 0;">{{__('ℹ️ Informazioni importanti:')}}</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li>{{__('🗓️ Il tuo abbonamento rimarrà attivo fino a')}} {{ $data['end_date'] }}</li>
            <li>{{__('📊 Potrai continuare ad accedere a tutti i servizi fino a tale data')}}</li>
            <li>{{__('💾 I tuoi dati rimarranno archiviati in modo sicuro secondo la nostra policy')}}</li>
        </ul>
    </div>
    
    <p style="margin-top: 20px;">{{__('Se hai riscontrato problemi con il nostro servizio o hai suggerimenti per migliorarlo, ti saremmo grati se volessi condividerli con noi rispondendo a questa email.')}}</p>
    
    <p style="margin-top: 20px;">{{__('Se dovessi cambiare idea, sappi che sarai sempre il benvenuto!')}}</p>
    
    <p style="margin-top: 20px;">{{__('Grazie per aver fatto parte della nostra community.')}}</p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{__('🤝 Il team di')}} {{ config('app.name') }}</p>
</body>
</html> 