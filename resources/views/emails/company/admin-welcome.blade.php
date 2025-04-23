<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Nuova azienda registrata su :app_name', ['app_name' => config('app.name')]) }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #003a5c;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        
        <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
        <h1 style="color: #0a4e75; margin-bottom: 20px;">{{ __('Nuova Azienda Registrata!') }}</h1>
        
        <p>{{ __('Ciao,') }}</p>
        
        <p>{{ __('Una nuova azienda si è registrata su :app_name.', ['app_name' => config('app.name')]) }}</p>
        
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; border: 1px solid #0a4e75; margin: 20px 0;">
            <h2 style="color: #0a4e75; font-size: 18px; margin-bottom: 15px;">{{ __('Dettagli Azienda:') }}</h2>
            <ul style="list-style-type: none; padding: 0;">
                <li style="margin-bottom: 10px;">🏢 {{ __('Nome Azienda: :company_name', ['company_name' => $data['company_name']]) }}</li>
                <li style="margin-bottom: 10px;">📧 {{ __('Email Azienda: :company_email', ['company_email' => $data['company_email']]) }}</li>
            </ul>
        </div>

        <p>{{ __('Puoi visualizzare e gestire i dettagli della nuova azienda nella tua dashboard di amministrazione.') }}</p>
        
        <p style="margin-top: 30px;">
            {{ __('Cordiali saluti,') }}<br>
            {{ __('Il team di :app_name', ['app_name' => config('app.name')]) }}
        </p>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #6c757d;">
            <p>{{ __('Questa è una email automatica, ti preghiamo di non rispondere direttamente a questo messaggio.') }}</p>
        </div>
    </div>
</body>
</html> 