<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijavite se na PazAriO</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(to right, #3b82f6, #1d4ed8); padding: 30px; text-align: center; }
        .content { padding: 40px 30px; }
        .button { display: inline-block; background-color: #3b82f6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 14px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: white; margin: 0; font-size: 28px;">PazAriO</h1>
            <p style="color: #e0e7ff; margin: 10px 0 0 0;">Brz pristup vašem nalogu</p>
        </div>
        
        <div class="content">
            <h2 style="color: #1f2937; margin-bottom: 20px;">Prijavite se jednim klikom</h2>
            
            <p style="color: #4b5563; line-height: 1.6; margin-bottom: 30px;">
                Zatražili ste pristup PazAriO platformi. Kliknite na dugme ispod da se prijavite ili kreirate nalog automatski.
            </p>
            
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ $magicLink->getUrl() }}" class="button">
                    🔗 Prijavite se sada
                </a>
            </div>
            
            <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 15px; margin: 30px 0;">
                <p style="color: #92400e; margin: 0; font-size: 14px;">
                    <strong>⏰ Link ističe za 15 minuta</strong><br>
                    Ako niste zatražili ovaj link, ignorišite ovaj email.
                </p>
            </div>
            
            <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                Ako dugme ne radi, kopirajte sledeći link u browser:<br>
                <a href="{{ $magicLink->getUrl() }}" style="color: #3b82f6;">{{ $magicLink->getUrl() }}</a>
            </p>
        </div>
        
        <div class="footer">
            <p>PazAriO - Kupuj i prodavaj bez ograničenja</p>
            <p>Ovaj email je automatski kreiran. Ne odgovarajte na njega.</p>
        </div>
    </div>
</body>
</html>