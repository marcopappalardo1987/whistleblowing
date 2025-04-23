<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="max-width: 600px; margin: 0 auto; padding: 20px; color: #003a5c;">
    
    <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
    <h1>{{__('Notifica di Pagamento Ricevuto')}}</h1>
    
    <p>{{__('Ciao')}},</p>
    
    <p>{{__('È stato ricevuto un nuovo pagamento sulla piattaforma.')}}</p>
    
    <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
        <h3 style="color: #0a4e75; margin-top: 0;">{{__('Dettagli del pagamento:')}}</h3>
        <p style="margin-bottom: 10px;">
            👤 <strong>{{__('Cliente:')}}</strong> {{ $data['user_name'] }}
        </p>
        <p style="margin-bottom: 10px;">
            🧾 <strong>{{__('Numero ordine:')}}</strong> #{{ $data['order_id'] }}
        </p>
        <p style="margin-bottom: 10px;">
            📦 <strong>{{__('Piano acquistato:')}}</strong> {{ $data['product_name'] }}
        </p>
        <p style="margin-bottom: 10px;">
            💳 <strong>{{__('Importo:')}}</strong> €{{ number_format($data['amount'], 2) }}
        </p>
        <p>
            📅 <strong>{{__('Data:')}}</strong> {{ $data['payment_date'] }}
        </p>
    </div>
    
    <div style="background-color: #e8f4fd; padding: 15px; border-radius: 5px; border: 1px solid #b8daff; margin: 20px 0;">
        <h3 style="color: #004085; margin-top: 0;">{{__('Azioni rapide:')}}</h3>
        <ul style="color: #004085; padding-left: 20px;">
            <li style="margin-bottom: 10px;">{{__('Verifica il pagamento nel pannello amministrativo')}}</li>
            <li>{{__('Controlla lo stato dell\'account del cliente')}}</li>
        </ul>
    </div>
    
    <p style="color: #6c757d; font-size: 0.9em; margin-top: 30px;">
        {{__('Questa è un\'email automatica generata dal sistema.')}}
    </p>
    
    <div style="font-size: 0.8em; color: #6c757d; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
        <p>{{__('© ')}} {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</body>
</html>