<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    <h1>Aggiornamento Dati Aziendali</h1>
    
    <p>Gentile {{ $data['user_name'] }},</p>
    
    <p>I dati della tua azienda sono stati aggiornati con successo.</p>
    
    <h2>Riepilogo delle modifiche:</h2>
    <ul>
        @foreach($data['updated_fields'] as $field => $value)
            <li><strong>{{ $field }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>
    
    <p>Cordiali saluti,<br>
    Il team di supporto</p>
</body>
</html> 