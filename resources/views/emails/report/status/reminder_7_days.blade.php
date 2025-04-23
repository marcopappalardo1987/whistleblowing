<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    
    <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
    <h1>{{__('Avviso: Scadenza Aggiornamento Stato Segnalazione')}}</h1>
    
    <p>{{__('Gentile')}} {{ $data['investigator_name'] }},</p>
    
    <p>{{__('Ti informiamo che il termine di 7 giorni per aggiornare lo stato del report #:report_id è scaduto. Sono trascorsi :days giorni dall\'ultimo aggiornamento.', [
        'report_id' => $data['report_id'],
        'days' => $data['days_inactive']
    ])}}</p>
    
    <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; border: 1px solid #ffeeba; margin: 20px 0;">
        <h3 style="color: #856404; margin-top: 0;">{{__('Dettagli Report:')}}</h3>
        <p>📄 <strong>{{__('ID Report:')}}</strong> #{{ $data['report_id'] }}</p>
        <p>🕒 <strong>{{__('Data della segnalazione:')}}</strong> {{ $data['last_update'] }}</p>
        <p>⚠️ <strong>{{__('Giorni trascorsi:')}}</strong> {{ $data['days_inactive'] }}</p>
    </div>
    
    <p>{{__('È fondamentale aggiornare lo stato di questa segnalazione il prima possibile per garantire il rispetto delle tempistiche previste dalla normativa sul whistleblowing.')}}</p>
    
    <p>{{__('Ti ricordiamo che una gestione tempestiva delle segnalazioni è fondamentale per:')}}
        <ul>
            <li>{{__('Garantire la protezione del segnalante')}}</li>
            <li>{{__('Mantenere l\'efficacia del sistema di whistleblowing')}}</li>
            <li>{{__('Rispettare gli obblighi di legge nella gestione delle segnalazioni')}}</li>
        </ul>
    </p>
    
    <p>{{__('Ti invitiamo ad accedere al sistema e aggiornare lo stato della segnalazione immediatamente.')}}</p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{ config('app.name') }}</p>
</body>
</html>
