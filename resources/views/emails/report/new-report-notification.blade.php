<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="color: #003a5c;">
    <h1>{{__('Nuova Segnalazione Ricevuta')}}</h1>
    
    <p>{{__('Gentile')}} {{ $data['investigator_name'] }},</p>
    
    <p>{{__('È stata ricevuta una nuova segnalazione per il branch')}} "{{ $data['branch_name'] }}" {{__('dell\'azienda')}} {{ $data['company_name'] }}.</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
        <h3 style="color: #0a4e75; margin-top: 0;">{{__('Dettagli Segnalazione:')}}</h3>
        <p style="margin-bottom: 10px;">
            🔍 <strong>{{__('ID Report:')}}</strong> #{{ $data['report_id'] }}
        </p>
        <p style="margin-bottom: 10px;">
            🏢 <strong>{{__('Branch:')}}</strong> {{ $data['branch_name'] }}
        </p>
        <p>
            📅 <strong>{{__('Data ricezione:')}}</strong> {{ $data['created_at'] }}
        </p>
    </div>
    
    <p>{{__('Per visualizzare i dettagli della segnalazione, accedi alla piattaforma.')}}</p>
    
    <p style="color: #6c757d; font-size: 0.9em;">
        {{__('Questa è un\'email automatica, si prega di non rispondere.')}}
    </p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{ config('app.name') }}</p>
</body>
</html> 