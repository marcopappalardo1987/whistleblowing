<!DOCTYPE html>
<html>
<head>
    <title>Benvenuto su {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #003a5c;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #0a4e75; margin-bottom: 20px;">Benvenuto!</h1>
        
        <p>Gentile {{ $data['user_name'] }},</p>
        
        <p>Ti diamo il benvenuto su {{ config('app.name') }}! Siamo lieti di averti con noi.</p>
        
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
            <h2 style="color: #0a4e75; font-size: 18px; margin-bottom: 15px;">Prossimi passi:</h2>
            <ul style="list-style-type: none; padding: 0;">
                <li style="margin-bottom: 10px;">✅ Completa il tuo profilo</li>
                <li style="margin-bottom: 10px;">📝 Inserisci i dati della tua azienda</li>
                <li style="margin-bottom: 10px;">🔍 Esplora le funzionalità disponibili</li>
            </ul>
        </div>

        <p>Se hai domande o hai bisogno di assistenza, non esitare a contattarci.</p>
        
        <p style="margin-top: 30px;">
            Cordiali saluti,<br>
            Il team di {{ config('app.name') }}
        </p>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #6c757d;">
            <p>Questa è una email automatica, ti preghiamo di non rispondere direttamente a questo messaggio.</p>
        </div>
    </div>
</body>
</html> 