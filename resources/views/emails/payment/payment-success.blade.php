<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="color: #003a5c;">
    <h1>{{__('Conferma di Pagamento')}}</h1>
    
    <p>{{__('Gentile')}} {{ $data['user_name'] }},</p>
    
    <p>{{__('Grazie per il tuo acquisto! Il pagamento è stato elaborato con successo.')}}</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
        <h3 style="color: #0a4e75; margin-top: 0;">{{__('Dettagli dell\'ordine:')}}</h3>
        <p style="margin-bottom: 10px;">
            🧾 <strong>{{__('Numero ordine:')}}</strong> #{{ $data['order_id'] }}
        </p>
        <p style="margin-bottom: 10px;">
            📦 <strong>{{__('Piano:')}}</strong> {{ $data['product_name'] }}
        </p>
        <p style="margin-bottom: 10px;">
            💳 <strong>{{__('Importo Pagato:')}}</strong> €{{ number_format($data['amount'], 2) }}
        </p>
        <p>
            📅 <strong>{{__('Data:')}}</strong> {{ $data['payment_date'] }}
        </p>
    </div>
    
    <div style="background-color: #edf7ed; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb; margin: 20px 0;">
        <h3 style="color: #155724; margin-top: 0;">{{__('Prossimi passi:')}}</h3>
        <ol style="color: #155724; padding-left: 20px;">
            <li style="margin-bottom: 10px;">{{__('Accedi alla tua dashboard')}}</li>
            <li>{{__('Inizia a utilizzare la piattaforma')}}</li>
        </ol>
    </div>
    
    <p>{{__('Se hai domande sul tuo abbonamento o hai bisogno di assistenza, non esitare a contattarci.')}}</p>
    
    <p style="color: #6c757d; font-size: 0.9em; margin-top: 30px;">
        {{__('Questa è un\'email automatica, si prega di non rispondere.')}}
    </p>
    
    <p>{{__('Cordiali saluti')}},<br>
    {{__('Il team di')}} {{ config('app.name') }}</p>

    <div style="font-size: 0.8em; color: #6c757d; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
        <p>{{__('Nota: Questa email serve come ricevuta del tuo pagamento. Ti consigliamo di conservarla per i tuoi archivi.')}}</p>
    </div>
</body>
</html> 