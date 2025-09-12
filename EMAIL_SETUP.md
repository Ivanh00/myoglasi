# Email Setup za MyOglasi

## Pregled

MyOglasi podržava opciono email verifikaciju za nove korisnike. Admin može da uključi/isključi ovu funkcionalnost u **Admin Panel → Podešavanja → Email**.

## SMTP Setup Opcije

### 1. Gmail SMTP (Besplatno - Preporučeno za manje sajtove)

U `.env` fajlu dodajte:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="MyOglasi"
```

**Napomene:**
- Koristite **App Password**, ne običnu šifru
- Limit: 500 email-ova dnevno
- [Setup guide](https://support.google.com/accounts/answer/185833)

### 2. Mailtrap (Za testiranje)

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@myoglasi.rs
MAIL_FROM_NAME="MyOglasi"
```

**Napomene:**
- Besplatno za development
- Email-ovi se ne šalju stvarno, samo se prikazuju u Mailtrap inbox-u

### 3. SendGrid (Profesionalno)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="MyOglasi"
```

### 4. Mailgun (Scalable)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-mailgun-username
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="MyOglasi"
```

## Email Verification Kontrola

1. **Idite u Admin Panel → Podešavanja → Email**
2. **Čekirajte "Zahtevaj email verifikaciju"** ako imate SMTP setup
3. **Ostavite isključeno** ako nemate SMTP server

## Test Email Setup-a

```bash
php artisan tinker
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

## Troubleshooting

- **Email se ne šalju:** Proverite SMTP credentials u `.env`
- **"Connection refused":** Proverite host i port
- **"Authentication failed":** Proverite username/password

## Bez Email Servisa

Ako nemate SMTP setup:
1. **Ostavite email verifikaciju isključenu** u admin panel-u
2. **Korisnici će moći** da koriste sve funkcionalnosti odmah
3. **Možete kasnije** uključiti kada setupujete email servis