<!-- Start Generation Here -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="max-width: 600px; margin: 0 auto; padding: 20px; color: #003a5c;">
    
    <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
    <h1>{{ __('Registrazione completa come Investigatore') }}</h1>
    
    <p>{{ __('Gentile') }} {{ $data['investigator_name'] }},</p>
    
    <p>{{ __('La tua registrazione come investigatore per l\'azienda') }} {{ $data['company_name'] }} {{__('è stata completata con successo.')}}</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
        <h2 style="color: #0a4e75;">{{__('Dettagli dell\'account:')}}</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 10px;">
                👤 <strong>{{__('Ruolo:')}}</strong> <span style="color: #0a4e75;">{{ __('Investigatore') }}</span>
            </li>
            <li>
                ✅ <strong>{{__('Branch:')}}</strong> <span style="color: #0a4e75;">{{ $data['branch_name'] }}</span>
            </li>
        </ul>
    </div>
    
    <p>{{__('Per iniziare, accedi alla piattaforma e inizia a visualizzare le segnalazioni.')}}</p>
    
    <p>{{__('Ti ringraziamo per la tua disponibilità e collaborazione.')}}</p>
    <p>{{__('Siamo entusiasti di averti nel nostro team!')}}</p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{ $data['company_name'] }}</p>
</body>
</html>
<!-- End Generation Here -->
