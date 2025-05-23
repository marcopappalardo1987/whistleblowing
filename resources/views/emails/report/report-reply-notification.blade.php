<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="color: #003a5c;">
    
    <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
    <h1>{{__('Nuova Risposta alla Segnalazione')}}</h1>
    
    <p>{{__('Gentile')}} {{ $data['investigator_name'] }},</p>
    
    <p>{{__('È stata ricevuta una nuova risposta alla segnalazione per il branch')}} "{{ $data['branch_name'] }}" {{__('dell\'azienda')}} {{ $data['company_name'] }}.</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
        <h3 style="color: #0a4e75; margin-top: 0;">{{__('Dettagli Segnalazione:')}}</h3>
        <p style="margin-bottom: 10px;">
            🔍 <strong>{{__('ID Report:')}}</strong> #{{ $data['report_id'] }}
        </p>
        <p style="margin-bottom: 10px;">
            🏢 <strong>{{__('Branch:')}}</strong> {{ $data['branch_name'] }}
        </p>
        <p>
            📅 <strong>{{__('Data risposta:')}}</strong> {{ $data['created_at'] }}
        </p>
    </div>
    
    <p>{{__('Per visualizzare la risposta, accedi alla piattaforma.')}}</p>
    
    <p style="color: #6c757d; font-size: 0.9em;">
        {{__('Questa è un\'email automatica, si prega di non rispondere.')}}
    </p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{ config('app.name') }}</p>
</body>
</html> 