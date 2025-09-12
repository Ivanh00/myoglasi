# OAuth Social Login Setup za MyOglasi

## Pregled

MyOglasi podržava login preko Google i Facebook naloga. Korisnici mogu da se prijave jednim klikom, a nalog se automatski kreira sa podacima iz social mreže.

## Google OAuth Setup

### 1. Google Cloud Console Setup

1. **Idite na [Google Cloud Console](https://console.cloud.google.com/)**
2. **Kreirajte novi projekat** ili odaberite postojeći
3. **APIs & Services → Credentials**
4. **Create Credentials → OAuth 2.0 Client ID**
5. **Application type:** Web application
6. **Authorized redirect URIs:** 
   ```
   http://localhost:8000/auth/google/callback
   https://yourdomain.com/auth/google/callback
   ```

### 2. .env Konfiguracija

```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```

### 3. config/services.php

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URL'),
],
```

## Facebook OAuth Setup

### 1. Facebook Developers Setup

1. **Idite na [Facebook Developers](https://developers.facebook.com/)**
2. **My Apps → Create App**
3. **Consumer ili Business** tip aplikacije
4. **Add Facebook Login product**
5. **Settings → Basic:**
   - App Domains: `yourdomain.com`
   - Website URL: `https://yourdomain.com`
6. **Facebook Login → Settings:**
   - Valid OAuth Redirect URIs:
     ```
     http://localhost:8000/auth/facebook/callback
     https://yourdomain.com/auth/facebook/callback
     ```

### 2. .env Konfiguracija

```env
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URL="${APP_URL}/auth/facebook/callback"
```

### 3. config/services.php

```php
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URL'),
],
```

## Dodavanje u config/services.php

Dodajte u `config/services.php` fajl:

```php
return [
    // ... existing services

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URL'),
    ],
];
```

## Kako funkcioniše

### User Flow:
1. **Korisnik klikne** "Google" ili "Facebook" dugme
2. **Redirect** na OAuth provider
3. **Korisnik odobri** pristup
4. **Callback** sa user podacima
5. **Find ili Create** user u bazi
6. **Auto-login** sa Remember me
7. **Redirect** na dashboard

### Automatic User Creation:
- **Email** iz social naloga
- **Name** iz social naloga (unique username)
- **Avatar** iz social naloga (ako dostupno)
- **Email verified** automatski
- **Random password** (korisnik ne zna, koristi social login)

## Development Testing

### Bez OAuth Setup:
- **Social dugmad** će biti vidljiva
- **Klik** će resultovati u grešku
- **Fallback** na obični login

### Sa OAuth Setup:
- **Funkcionalan social login**
- **Automatsko kreiranje naloga**
- **Smooth user experience**

## Security Features

- **Unique username generation**
- **Email verification bypass** za social users
- **Avatar import** iz social platformi
- **Error handling** sa fallback na obični login
- **Provider validation** (samo google/facebook)

## Troubleshooting

- **"Client ID not configured":** Proverite GOOGLE_CLIENT_ID u .env
- **"Redirect URI mismatch":** Proverite redirect URL u OAuth aplikaciji
- **"App not approved":** Facebook aplikacije trebaju review za production
- **"Invalid scopes":** Proverite da li ste omogućili email permission