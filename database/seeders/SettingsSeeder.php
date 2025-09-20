<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Payment Settings
            [
                'key' => 'listing_fee_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payments',
                'description' => 'Da li je naplaćivanje oglasa uključeno'
            ],
            [
                'key' => 'listing_fee_amount',
                'value' => '10',
                'type' => 'integer',
                'group' => 'payments',
                'description' => 'Cena po oglasu u dinarima'
            ],
            [
                'key' => 'monthly_plan_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payments',
                'description' => 'Da li je mesečni plan uključen'
            ],
            [
                'key' => 'monthly_plan_price',
                'value' => '500',
                'type' => 'integer',
                'group' => 'payments',
                'description' => 'Cena mesečnog plana u dinarima'
            ],
            [
                'key' => 'yearly_plan_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payments',
                'description' => 'Da li je godišnji plan uključen'
            ],
            [
                'key' => 'yearly_plan_price',
                'value' => '5000',
                'type' => 'integer',
                'group' => 'payments',
                'description' => 'Cena godišnjeg plana u dinarima'
            ],
            [
                'key' => 'free_listings_per_month',
                'value' => '0',
                'type' => 'integer',
                'group' => 'payments',
                'description' => 'Broj besplatnih oglasa mesečno (0 = nema besplatnih)'
            ],

            // General Settings
            [
                'key' => 'site_name',
                'value' => 'PazAriO',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Naziv sajta'
            ],
            [
                'key' => 'max_images_per_listing',
                'value' => '10',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'Maksimalni broj slika po oglasu'
            ],
            [
                'key' => 'listing_auto_expire_days',
                'value' => '60',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'Broj dana posle kojih oglas automatski ističe'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Režim održavanja sajta'
            ],

            // Email Settings
            [
                'key' => 'admin_email',
                'value' => 'admin@pazario.rs',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email adresa administratora'
            ],
            [
                'key' => 'support_email',
                'value' => 'support@pazario.rs',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email adresa podrške'
            ],

            // Bank Details
            [
                'key' => 'bank_account_number',
                'value' => '265-0000000003456-78',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Broj računa za uplate'
            ],
            [
                'key' => 'bank_name',
                'value' => 'Intesa Banka a.d. Beograd',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Naziv banke'
            ],
            [
                'key' => 'company_name',
                'value' => 'PazAriO d.o.o.',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Naziv kompanije'
            ],
            [
                'key' => 'company_address',
                'value' => 'Bulevar Oslobođenja 123, 11000 Beograd',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Adresa kompanije'
            ],
            [
                'key' => 'company_pib',
                'value' => '123456789',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'PIB kompanije'
            ],
            [
                'key' => 'payment_code_physical',
                'value' => '289',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Šifra plaćanja za fizička lica'
            ],
            [
                'key' => 'payment_code_legal',
                'value' => '221',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Šifra plaćanja za pravna lica'
            ],
            [
                'key' => 'model_number_physical',
                'value' => '97',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Model broj za fizička lica'
            ],
            [
                'key' => 'model_number_legal',
                'value' => '97',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Model broj za pravna lica'
            ],
            [
                'key' => 'reference_number_template',
                'value' => '20-10-{user_id}',
                'type' => 'string',
                'group' => 'banking',
                'description' => 'Template poziva na broj (odobrenja) - {user_id} će biti zamenjen ID-jem korisnika'
            ],

            // Promotion Settings
            [
                'key' => 'promotion_featured_category_price',
                'value' => '75',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Cena za Top kategorije promociju (u kreditima)'
            ],
            [
                'key' => 'promotion_featured_category_days',
                'value' => '3',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Trajanje Top kategorije promocije (u danima)'
            ],
            [
                'key' => 'promotion_featured_homepage_price',
                'value' => '100',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Cena za Top glavne strane promociju (u kreditima)'
            ],
            [
                'key' => 'promotion_featured_homepage_days',
                'value' => '1',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Trajanje Top glavne strane promocije (u danima)'
            ],
            [
                'key' => 'promotion_highlighted_price',
                'value' => '50',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Cena za Istaknut oglas promociju (u kreditima)'
            ],
            [
                'key' => 'promotion_highlighted_days',
                'value' => '7',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Trajanje Istaknut oglas promocije (u danima)'
            ],
            [
                'key' => 'promotion_auto_refresh_price',
                'value' => '30',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Cena za Automatsko osvežavanje (u kreditima)'
            ],
            [
                'key' => 'promotion_auto_refresh_days',
                'value' => '7',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Trajanje Automatskog osvežavanja (u danima)'
            ],
            [
                'key' => 'promotion_double_images_price',
                'value' => '25',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Cena za Dupliraj broj slika (u kreditima)'
            ],
            [
                'key' => 'promotion_double_images_days',
                'value' => '14',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Trajanje Dupliraj broj slika (u danima)'
            ],
            [
                'key' => 'promotion_extended_duration_price',
                'value' => '40',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Cena za Produženu trajnost (u kreditima)'
            ],
            [
                'key' => 'promotion_extended_duration_days',
                'value' => '30',
                'type' => 'integer',
                'group' => 'promotions',
                'description' => 'Trajanje Produžene trajnosti (dodatni dani)'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}