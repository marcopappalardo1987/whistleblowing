<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    
    <img class="logo" src="{{ asset('logo.png') }}" alt="{{ __('Whistleblowing Tool Logo') }}" style="max-width: 165px;">
    <h1 style="color: #2d3748;">{{__('📊 Aggiornamento Dati Aziendali')}}</h1>
    
    <p>{{__('Gentile')}} {{ $data['user_name'] }},</p>
    
    <p style="margin-bottom: 20px;">{{__('🎉 I dati della tua azienda sono stati aggiornati con successo!')}}</p>
    
    <h2 style="color: #00796b;">{{__('🔍 Riepilogo delle modifiche:')}}</h2>
    <ul style="list-style-type: none; padding-left: 0;">
        <li><strong>{{__('🏢 Ragione Sociale:')}}</strong> {{ $data['updated_fields']['legal_name'] }}</li>
        <li><strong>{{__('💼 Partita IVA:')}}</strong> {{ $data['updated_fields']['vat_number'] }}</li>
        <li><strong>{{__('🆔 Codice Fiscale:')}}</strong> {{ $data['updated_fields']['tax_code'] }}</li>
        <li><strong>{{__('📄 Codice SDI:')}}</strong> {{ $data['updated_fields']['sdi_code'] }}</li>
        <li><strong>{{__('🏠 Indirizzo Completo:')}}</strong> {{ $data['updated_fields']['full_address'] }}</li>
        <li><strong>{{__('🌍 Paese:')}}</strong> {{ $data['updated_fields']['country'] }}</li>
        <li><strong>{{__('📈 Numero REA:')}}</strong> {{ $data['updated_fields']['rea_number'] }}</li>
        <li><strong>{{__('📝 Numero di Registrazione:')}}</strong> {{ $data['updated_fields']['registration_number'] }}</li>
        <li><strong>{{__('📧 Email:')}}</strong> {{ $data['updated_fields']['email'] }}</li>
        <li><strong>{{__('📞 Numero di Telefono:')}}</strong> {{ $data['updated_fields']['phone_number'] }}</li>
        <li><strong>{{__('👤 Contatto Amministrativo:')}}</strong> {{ $data['updated_fields']['administrative_contact'] }}</li>
        <li><strong>{{__('💳 Iban:')}}</strong> {{ $data['updated_fields']['iban'] }}</li>
        <li><strong>{{__('🏦 Nome Banca:')}}</strong> {{ $data['updated_fields']['bank_name'] }}</li>
    </ul>
    
    <p style="margin-top: 20px;">{{__('Cordiali saluti')}},<br>
    {{__('🤝 Il team di supporto')}}</p>
</body>
</html> 