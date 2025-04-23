<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="max-width: 600px; margin: 0 auto; padding: 20px; color: #333;">
    
    <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
    <h1 style="color: #2d3748;">{{__('🔔 Notifica: Abbonamento Cancellato')}}</h1>
    
    <p>{{__('Ciao')}},</p>
    
    <p style="margin-bottom: 20px;">{{__('Ti informiamo che l\'azienda')}} {{ $data['user_name'] }} {{__('ha cancellato il proprio abbonamento.')}}</p>
    
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; border: 1px solid #dee2e6; margin: 20px 0;">
        <h2 style="color: #00796b; margin-top: 0;">{{__('ℹ️ Dettagli dell\'abbonamento:')}}</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li>{{__('🏢 Azienda:')}} {{ $data['user_name'] }}</li>
            <li>{{__('🗓️ Data di fine abbonamento:')}} {{ $data['end_date'] }}</li>
            <li>{{__('📊 L\'azienda avrà accesso ai servizi fino alla data di scadenza')}}</li>
        </ul>
    </div>
    
    <p style="margin-top: 20px;">{{__('Potrebbe essere utile contattare l\'azienda per comprendere le ragioni della cancellazione e valutare eventuali azioni di retention.')}}</p>
    
    <p style="margin-top: 20px;">{{__('Questa è una notifica automatica. Non è richiesta alcuna azione immediata.')}}</p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{__('🤖 Sistema di notifica di')}} {{ config('app.name') }}</p>
</body>
</html>