<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="max-width: 600px; margin: 0 auto; padding: 20px; color: #003a5c;">
    <h1>{{__('Benvenuto nel Programma di Affiliazione')}}</h1>
    
    <p>{{__('Gentile')}} {{ $data['affiliate_name'] }},</p>
    
    <p>{{__('Grazie per esserti registrato come affiliato di')}} {{ config('app.name') }}. {{__('Siamo entusiasti di averti a bordo!')}}</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
        <h3 style="color: #0a4e75; margin-top: 0;">{{__('I tuoi dati di affiliazione:')}}</h3>
        <p style="margin-bottom: 10px;">
            👤 <strong>{{__('ID Affiliato:')}}</strong> #{{ $data['affiliate_id'] }}
        </p>
        <p style="margin-bottom: 10px;">
            🔗 <strong>{{__('Qui puoi trovare i link da condividere per consentire ai tuoi affiliati di registrarsi:')}}</strong><br>
            <a href="{{ $data['affiliate_link'] }}" style="color: #0a4e75; word-break: break-all;">{{ $data['affiliate_link'] }}</a>
        </p>
    </div>
    
    <div style="background-color: #edf7ed; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb; margin: 20px 0;">
        <h3 style="color: #155724; margin-top: 0;">{{__('Come iniziare:')}}</h3>
        <ol style="color: #155724; padding-left: 20px;">
            <li style="margin-bottom: 10px;">{{__('Accedi alla tua dashboard di affiliato')}}</li>
            <li style="margin-bottom: 10px;">{{__('Copia il tuo link di affiliazione personale')}}</li>
            <li style="margin-bottom: 10px;">{{__('Condividi il link con il tuo network')}}</li>
            <li>{{__('Monitora le tue performance e i guadagni nella dashboard')}}</li>
        </ol>
    </div>
    
    <p>{{__('Se hai domande o hai bisogno di assistenza, non esitare a contattare il nostro team di supporto.')}}</p>
    
    <p>{{__('Ti auguriamo grande successo nel tuo percorso di affiliazione!')}}</p>
    
    <p style="color: #6c757d; font-size: 0.9em; margin-top: 30px;">
        {{__('Questa è un\'email automatica, si prega di non rispondere.')}}
    </p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{__('Il team di')}} {{ config('app.name') }}</p>
</body>
</html> 