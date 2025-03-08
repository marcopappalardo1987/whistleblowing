<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="max-width: 600px; margin: 0 auto; padding: 20px; color: #003a5c;">
    <h1>{{__('Nuovo Affiliato Registrato')}}</h1>
    
    <p>{{__('Siamo lieti di informarti che un nuovo affiliato si è registrato con successo nel programma di affiliazione di')}} {{ config('app.name') }}.</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
        <h3 style="color: #0a4e75; margin-top: 0;">{{__('Dati del nuovo affiliato:')}}</h3>
        <p style="margin-bottom: 10px;">
            👤 <strong>{{__('Nome Affiliato:')}}</strong> {{ $data['affiliate_name'] }}
        </p>
        <p style="margin-bottom: 10px;">
            📧 <strong>{{__('Email Affiliato:')}}</strong> {{ $data['affiliate_email'] }}
        </p>
        <p style="margin-bottom: 10px;">
            👤 <strong>{{__('ID Affiliato:')}}</strong> #{{ $data['affiliate_id'] }}
        </p>
    </div>
    
    <p>{{__('Puoi visualizzare e gestire i dettagli del nuovo affiliato nella tua dashboard di amministrazione.')}}</p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{__('Il team di')}} {{ config('app.name') }}</p>
</body>
</html>
