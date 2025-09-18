<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class Settings extends Component
{
    use WithFileUploads;

    public $activeTab = 'payments';
    
    // Payment Settings
    public $listingFeeEnabled;
    public $listingFeeAmount;
    public $monthlyPlanEnabled;
    public $monthlyPlanPrice;
    public $yearlyPlanEnabled;
    public $yearlyPlanPrice;
    public $freeListingsPerMonth;
    
    // General Settings  
    public $siteName;
    public $maxImagesPerListing;
    public $listingAutoExpireDays;
    public $maintenanceMode;
    public $monthlyListingLimit;
    public $minimumCreditTransfer;
    public $showLastSeen;
    public $serviceFeeEnabled;
    public $serviceFeeAmount;
    
    // Credit Earning Settings
    public $gameCreditEnabled;
    public $gameCreditAmount;
    public $dailyContestEnabled;
    public $dailyContestAmount;
    public $gameLeaderboardEnabled;
    public $gameLeaderboardBonus;
    
    // Promotion Settings
    public $promotionFeaturedCategoryPrice;
    public $promotionFeaturedCategoryDays;
    public $promotionFeaturedHomepagePrice;
    public $promotionFeaturedHomepageDays;
    public $promotionHighlightedPrice;
    public $promotionHighlightedDays;
    public $promotionAutoRefreshPrice;
    public $promotionAutoRefreshDays;
    public $promotionDoubleImagesPrice;
    public $promotionDoubleImagesDays;
    public $promotionExtendedDurationPrice;
    public $promotionExtendedDurationDays;
    
    // Email Settings
    public $adminEmail;
    public $supportEmail;
    public $emailVerificationEnabled;
    public $magicLinkEnabled;
    public $googleLoginEnabled;
    public $facebookLoginEnabled;
    
    // Banking Settings
    public $bankAccountNumber;
    public $bankName;
    public $companyName;
    public $companyAddress;
    public $companyPib;
    public $paymentCodePhysical;
    public $paymentCodeLegal;
    public $modelNumberPhysical;
    public $modelNumberLegal;
    public $referenceNumberTemplate;

    // Auction Settings
    public $auctionDefaultBidIncrement;
    public $auctionMaxExtensions;
    public $auctionExtensionTime;
    public $auctionExtensionTriggerTime;

    // Database Backup/Restore
    public $backupFile;
    public $showRestoreConfirmation = false;
    public $databaseType = '';
    public $databaseSize = '';
    public $tableCount = 0;
    public $lastBackupDate = null;

    protected $rules = [
        'listingFeeEnabled' => 'required|boolean',
        'listingFeeAmount' => 'required|integer|min:1|max:10000',
        'monthlyPlanEnabled' => 'required|boolean',
        'monthlyPlanPrice' => 'required|integer|min:100|max:50000',
        'yearlyPlanEnabled' => 'required|boolean',
        'yearlyPlanPrice' => 'required|integer|min:1000|max:500000',
        'freeListingsPerMonth' => 'required|integer|min:0|max:100',
        'siteName' => 'required|string|max:100',
        'maxImagesPerListing' => 'required|integer|min:1|max:50',
        'listingAutoExpireDays' => 'required|integer|min:7|max:365',
        'maintenanceMode' => 'required|boolean',
        'monthlyListingLimit' => 'required|integer|min:1|max:1000',
        'minimumCreditTransfer' => 'required|integer|min:1|max:10000',
        'showLastSeen' => 'required|boolean',
        'serviceFeeEnabled' => 'required|boolean',
        'serviceFeeAmount' => 'required|integer|min:1|max:10000',
        'gameCreditEnabled' => 'required|boolean',
        'gameCreditAmount' => 'required|integer|min:1|max:1000',
        'dailyContestEnabled' => 'required|boolean',
        'dailyContestAmount' => 'required|integer|min:1|max:1000',
        'gameLeaderboardEnabled' => 'required|boolean',
        'gameLeaderboardBonus' => 'required|integer|min:1|max:1000',
        // Promotion validation
        'promotionFeaturedCategoryPrice' => 'required|integer|min:1|max:10000',
        'promotionFeaturedCategoryDays' => 'required|integer|min:1|max:365',
        'promotionFeaturedHomepagePrice' => 'required|integer|min:1|max:10000',
        'promotionFeaturedHomepageDays' => 'required|integer|min:1|max:365',
        'promotionHighlightedPrice' => 'required|integer|min:1|max:10000',
        'promotionHighlightedDays' => 'required|integer|min:1|max:365',
        'promotionAutoRefreshPrice' => 'required|integer|min:1|max:10000',
        'promotionAutoRefreshDays' => 'required|integer|min:1|max:365',
        'promotionDoubleImagesPrice' => 'required|integer|min:1|max:10000',
        'promotionDoubleImagesDays' => 'required|integer|min:1|max:365',
        'promotionExtendedDurationPrice' => 'required|integer|min:1|max:10000',
        'promotionExtendedDurationDays' => 'required|integer|min:1|max:365',
        'adminEmail' => 'required|email|max:255',
        'supportEmail' => 'required|email|max:255',
        'emailVerificationEnabled' => 'required|boolean',
        'magicLinkEnabled' => 'required|boolean',
        'googleLoginEnabled' => 'required|boolean',
        'facebookLoginEnabled' => 'required|boolean',
        'bankAccountNumber' => 'required|string|max:100',
        'bankName' => 'required|string|max:255',
        'companyName' => 'required|string|max:255',
        'companyAddress' => 'required|string|max:500',
        'companyPib' => 'required|string|max:20',
        'paymentCodePhysical' => 'required|string|max:10',
        'paymentCodeLegal' => 'required|string|max:10',
        'modelNumberPhysical' => 'required|string|max:10',
        'modelNumberLegal' => 'required|string|max:10',
        'referenceNumberTemplate' => 'required|string|max:100',
        'auctionDefaultBidIncrement' => 'required|integer|min:10|max:10000',
        'auctionMaxExtensions' => 'required|integer|min:1|max:20',
        'auctionExtensionTime' => 'required|integer|min:1|max:10',
        'auctionExtensionTriggerTime' => 'required|integer|min:1|max:10',
    ];

    public function mount()
    {
        $this->loadSettings();

        // Load database info when data tab is active
        if ($this->activeTab === 'data') {
            $this->loadDatabaseInfo();
        }
    }

    public function loadSettings()
    {
        // Payment Settings
        $this->listingFeeEnabled = Setting::get('listing_fee_enabled', true);
        $this->listingFeeAmount = Setting::get('listing_fee_amount', 10);
        $this->monthlyPlanEnabled = Setting::get('monthly_plan_enabled', false);
        $this->monthlyPlanPrice = Setting::get('monthly_plan_price', 500);
        $this->yearlyPlanEnabled = Setting::get('yearly_plan_enabled', false);
        $this->yearlyPlanPrice = Setting::get('yearly_plan_price', 5000);
        $this->freeListingsPerMonth = Setting::get('free_listings_per_month', 0);
        
        // General Settings
        $this->siteName = Setting::get('site_name', 'PazAriO');
        $this->maxImagesPerListing = Setting::get('max_images_per_listing', 10);
        $this->listingAutoExpireDays = Setting::get('listing_auto_expire_days', 60);
        $this->maintenanceMode = Setting::get('maintenance_mode', false);
        $this->monthlyListingLimit = Setting::get('monthly_listing_limit', 50);
        $this->minimumCreditTransfer = Setting::get('minimum_credit_transfer', 10);
        $this->showLastSeen = Setting::get('show_last_seen', true);
        $this->serviceFeeEnabled = Setting::get('service_fee_enabled', true);
        $this->serviceFeeAmount = Setting::get('service_fee_amount', 100);
        $this->gameCreditEnabled = Setting::get('game_credit_enabled', true);
        $this->gameCreditAmount = Setting::get('game_credit_amount', 100);
        $this->dailyContestEnabled = Setting::get('daily_contest_enabled', true);
        $this->dailyContestAmount = Setting::get('daily_contest_amount', 100);
        $this->gameLeaderboardEnabled = Setting::get('game_leaderboard_enabled', true);
        $this->gameLeaderboardBonus = Setting::get('game_leaderboard_bonus', 50);
        
        // Promotion Settings
        $this->promotionFeaturedCategoryPrice = Setting::get('promotion_featured_category_price', 100);
        $this->promotionFeaturedCategoryDays = Setting::get('promotion_featured_category_days', 7);
        $this->promotionFeaturedHomepagePrice = Setting::get('promotion_featured_homepage_price', 200);
        $this->promotionFeaturedHomepageDays = Setting::get('promotion_featured_homepage_days', 3);
        $this->promotionHighlightedPrice = Setting::get('promotion_highlighted_price', 50);
        $this->promotionHighlightedDays = Setting::get('promotion_highlighted_days', 14);
        $this->promotionAutoRefreshPrice = Setting::get('promotion_auto_refresh_price', 80);
        $this->promotionAutoRefreshDays = Setting::get('promotion_auto_refresh_days', 30);
        $this->promotionDoubleImagesPrice = Setting::get('promotion_double_images_price', 30);
        $this->promotionDoubleImagesDays = Setting::get('promotion_double_images_days', 14);
        $this->promotionExtendedDurationPrice = Setting::get('promotion_extended_duration_price', 60);
        $this->promotionExtendedDurationDays = Setting::get('promotion_extended_duration_days', 30);
        
        // Email Settings
        $this->adminEmail = Setting::get('admin_email', 'admin@pazario.rs');
        $this->supportEmail = Setting::get('support_email', 'support@pazario.rs');
        $this->emailVerificationEnabled = Setting::get('email_verification_enabled', false);
        $this->magicLinkEnabled = Setting::get('magic_link_enabled', false);
        $this->googleLoginEnabled = Setting::get('google_login_enabled', false);
        $this->facebookLoginEnabled = Setting::get('facebook_login_enabled', false);
        
        // Banking Settings
        $this->bankAccountNumber = Setting::get('bank_account_number', '265-0000000003456-78');
        $this->bankName = Setting::get('bank_name', 'Intesa Banka a.d. Beograd');
        $this->companyName = Setting::get('company_name', 'PazAriO d.o.o.');
        $this->companyAddress = Setting::get('company_address', 'Bulevar Oslobođenja 123, 11000 Beograd');
        $this->companyPib = Setting::get('company_pib', '123456789');
        $this->paymentCodePhysical = Setting::get('payment_code_physical', '289');
        $this->paymentCodeLegal = Setting::get('payment_code_legal', '221');
        $this->modelNumberPhysical = Setting::get('model_number_physical', '97');
        $this->modelNumberLegal = Setting::get('model_number_legal', '97');
        $this->referenceNumberTemplate = Setting::get('reference_number_template', '20-10-{user_id}');
        
        // Auction Settings
        $this->auctionDefaultBidIncrement = Setting::get('auction_default_bid_increment', 50);
        $this->auctionMaxExtensions = Setting::get('auction_max_extensions', 10);
        $this->auctionExtensionTime = Setting::get('auction_extension_time', 3);
        $this->auctionExtensionTriggerTime = Setting::get('auction_extension_trigger_time', 3);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;

        // Load database info when switching to data tab
        if ($tab === 'data') {
            $this->loadDatabaseInfo();
        }
    }

    public function savePaymentSettings()
    {
        $this->validate([
            'listingFeeEnabled' => 'required|boolean',
            'listingFeeAmount' => 'required|integer|min:1|max:10000',
            'monthlyPlanEnabled' => 'required|boolean',
            'monthlyPlanPrice' => 'required|integer|min:100|max:50000',
            'yearlyPlanEnabled' => 'required|boolean',
            'yearlyPlanPrice' => 'required|integer|min:1000|max:500000',
            'freeListingsPerMonth' => 'required|integer|min:0|max:100',
        ]);

        Setting::set('listing_fee_enabled', $this->listingFeeEnabled, 'boolean', 'payments');
        Setting::set('listing_fee_amount', $this->listingFeeAmount, 'integer', 'payments');
        Setting::set('monthly_plan_enabled', $this->monthlyPlanEnabled, 'boolean', 'payments');
        Setting::set('monthly_plan_price', $this->monthlyPlanPrice, 'integer', 'payments');
        Setting::set('yearly_plan_enabled', $this->yearlyPlanEnabled, 'boolean', 'payments');
        Setting::set('yearly_plan_price', $this->yearlyPlanPrice, 'integer', 'payments');
        Setting::set('free_listings_per_month', $this->freeListingsPerMonth, 'integer', 'payments');

        session()->flash('success', 'Podešavanja plaćanja su uspešno sačuvana.');
    }

    public function saveGeneralSettings()
    {
        $this->validate([
            'siteName' => 'required|string|max:100',
            'maxImagesPerListing' => 'required|integer|min:1|max:50',
            'listingAutoExpireDays' => 'required|integer|min:7|max:365',
            'maintenanceMode' => 'required|boolean',
            'monthlyListingLimit' => 'required|integer|min:1|max:1000',
            'minimumCreditTransfer' => 'required|integer|min:1|max:10000',
            'showLastSeen' => 'required|boolean',
            'serviceFeeEnabled' => 'required|boolean',
            'serviceFeeAmount' => 'required|integer|min:1|max:10000',
            'gameCreditEnabled' => 'required|boolean',
            'gameCreditAmount' => 'required|integer|min:1|max:1000',
            'dailyContestEnabled' => 'required|boolean',
            'dailyContestAmount' => 'required|integer|min:1|max:1000',
            'gameLeaderboardEnabled' => 'required|boolean',
            'gameLeaderboardBonus' => 'required|integer|min:1|max:1000',
            'promotionFeaturedCategoryPrice' => 'required|integer|min:1|max:10000',
            'promotionFeaturedCategoryDays' => 'required|integer|min:1|max:365',
            'promotionFeaturedHomepagePrice' => 'required|integer|min:1|max:10000',
            'promotionFeaturedHomepageDays' => 'required|integer|min:1|max:365',
            'promotionHighlightedPrice' => 'required|integer|min:1|max:10000',
            'promotionHighlightedDays' => 'required|integer|min:1|max:365',
            'promotionAutoRefreshPrice' => 'required|integer|min:1|max:10000',
            'promotionAutoRefreshDays' => 'required|integer|min:1|max:365',
            'promotionDoubleImagesPrice' => 'required|integer|min:1|max:10000',
            'promotionDoubleImagesDays' => 'required|integer|min:1|max:365',
            'promotionExtendedDurationPrice' => 'required|integer|min:1|max:10000',
            'promotionExtendedDurationDays' => 'required|integer|min:1|max:365',
        ]);

        Setting::set('site_name', $this->siteName, 'string', 'general');
        Setting::set('max_images_per_listing', $this->maxImagesPerListing, 'integer', 'general');
        Setting::set('listing_auto_expire_days', $this->listingAutoExpireDays, 'integer', 'general');
        Setting::set('maintenance_mode', $this->maintenanceMode, 'boolean', 'general');
        Setting::set('monthly_listing_limit', $this->monthlyListingLimit, 'integer', 'general');
        Setting::set('minimum_credit_transfer', $this->minimumCreditTransfer, 'integer', 'general');
        Setting::set('show_last_seen', $this->showLastSeen, 'boolean', 'general');
        Setting::set('service_fee_enabled', $this->serviceFeeEnabled, 'boolean', 'general');
        Setting::set('service_fee_amount', $this->serviceFeeAmount, 'integer', 'general');
        Setting::set('game_credit_enabled', $this->gameCreditEnabled, 'boolean', 'general');
        Setting::set('game_credit_amount', $this->gameCreditAmount, 'integer', 'general');
        Setting::set('daily_contest_enabled', $this->dailyContestEnabled, 'boolean', 'general');
        Setting::set('daily_contest_amount', $this->dailyContestAmount, 'integer', 'general');
        Setting::set('game_leaderboard_enabled', $this->gameLeaderboardEnabled, 'boolean', 'general');
        Setting::set('game_leaderboard_bonus', $this->gameLeaderboardBonus, 'integer', 'general');
        
        // Save promotion settings
        Setting::set('promotion_featured_category_price', $this->promotionFeaturedCategoryPrice, 'integer', 'promotions');
        Setting::set('promotion_featured_category_days', $this->promotionFeaturedCategoryDays, 'integer', 'promotions');
        Setting::set('promotion_featured_homepage_price', $this->promotionFeaturedHomepagePrice, 'integer', 'promotions');
        Setting::set('promotion_featured_homepage_days', $this->promotionFeaturedHomepageDays, 'integer', 'promotions');
        Setting::set('promotion_highlighted_price', $this->promotionHighlightedPrice, 'integer', 'promotions');
        Setting::set('promotion_highlighted_days', $this->promotionHighlightedDays, 'integer', 'promotions');
        Setting::set('promotion_auto_refresh_price', $this->promotionAutoRefreshPrice, 'integer', 'promotions');
        Setting::set('promotion_auto_refresh_days', $this->promotionAutoRefreshDays, 'integer', 'promotions');
        Setting::set('promotion_double_images_price', $this->promotionDoubleImagesPrice, 'integer', 'promotions');
        Setting::set('promotion_double_images_days', $this->promotionDoubleImagesDays, 'integer', 'promotions');
        Setting::set('promotion_extended_duration_price', $this->promotionExtendedDurationPrice, 'integer', 'promotions');
        Setting::set('promotion_extended_duration_days', $this->promotionExtendedDurationDays, 'integer', 'promotions');

        session()->flash('success', 'Opšta podešavanja su uspešno sačuvana.');
    }

    public function saveEmailSettings()
    {
        $this->validate([
            'adminEmail' => 'required|email|max:255',
            'supportEmail' => 'required|email|max:255',
            'emailVerificationEnabled' => 'required|boolean',
            'magicLinkEnabled' => 'required|boolean',
            'googleLoginEnabled' => 'required|boolean',
            'facebookLoginEnabled' => 'required|boolean',
        ]);

        Setting::set('admin_email', $this->adminEmail, 'string', 'email');
        Setting::set('support_email', $this->supportEmail, 'string', 'email');
        Setting::set('email_verification_enabled', $this->emailVerificationEnabled, 'boolean', 'email');
        Setting::set('magic_link_enabled', $this->magicLinkEnabled, 'boolean', 'email');
        Setting::set('google_login_enabled', $this->googleLoginEnabled, 'boolean', 'email');
        Setting::set('facebook_login_enabled', $this->facebookLoginEnabled, 'boolean', 'email');

        session()->flash('success', 'Email podešavanja su uspešno sačuvana.');
    }

    public function saveBankingSettings()
    {
        $this->validate([
            'bankAccountNumber' => 'required|string|max:100',
            'bankName' => 'required|string|max:255',
            'companyName' => 'required|string|max:255',
            'companyAddress' => 'required|string|max:500',
            'companyPib' => 'required|string|max:20',
            'paymentCodePhysical' => 'required|string|max:10',
            'paymentCodeLegal' => 'required|string|max:10',
            'modelNumberPhysical' => 'required|string|max:10',
            'modelNumberLegal' => 'required|string|max:10',
            'referenceNumberTemplate' => 'required|string|max:100',
        ]);

        Setting::set('bank_account_number', $this->bankAccountNumber, 'string', 'banking');
        Setting::set('bank_name', $this->bankName, 'string', 'banking');
        Setting::set('company_name', $this->companyName, 'string', 'banking');
        Setting::set('company_address', $this->companyAddress, 'string', 'banking');
        Setting::set('company_pib', $this->companyPib, 'string', 'banking');
        Setting::set('payment_code_physical', $this->paymentCodePhysical, 'string', 'banking');
        Setting::set('payment_code_legal', $this->paymentCodeLegal, 'string', 'banking');
        Setting::set('model_number_physical', $this->modelNumberPhysical, 'string', 'banking');
        Setting::set('model_number_legal', $this->modelNumberLegal, 'string', 'banking');
        Setting::set('reference_number_template', $this->referenceNumberTemplate, 'string', 'banking');

        session()->flash('success', 'Bankovna podešavanja su uspešno sačuvana.');
    }

    public function saveAuctionSettings()
    {
        $this->validate([
            'auctionDefaultBidIncrement' => 'required|integer|min:10|max:10000',
            'auctionMaxExtensions' => 'required|integer|min:1|max:20',
            'auctionExtensionTime' => 'required|integer|min:1|max:10',
            'auctionExtensionTriggerTime' => 'required|integer|min:1|max:10',
        ]);

        Setting::set('auction_default_bid_increment', $this->auctionDefaultBidIncrement, 'integer', 'auctions');
        Setting::set('auction_max_extensions', $this->auctionMaxExtensions, 'integer', 'auctions');
        Setting::set('auction_extension_time', $this->auctionExtensionTime, 'integer', 'auctions');
        Setting::set('auction_extension_trigger_time', $this->auctionExtensionTriggerTime, 'integer', 'auctions');

        session()->flash('success', 'Podešavanja aukcija su uspešno sačuvana.');
    }

    // Database Backup/Restore Methods
    public function exportDatabase()
    {
        try {
            // Get database connection details
            $connection = config('database.default');
            $database = config("database.connections.{$connection}.database");

            // Prepare backup data
            $backup = [
                'app' => 'PazAriO',
                'version' => '1.0',
                'backup_date' => now()->toDateTimeString(),
                'database_type' => $connection,
                'tables' => []
            ];

            // Get all tables based on database type
            if ($connection === 'sqlite') {
                // For SQLite
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

                foreach ($tables as $table) {
                    $tableName = $table->name;

                    // Get table structure for SQLite
                    $createTable = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name=?", [$tableName])[0];
                    $createStatement = $createTable->sql;

                    // Get table data
                    $data = DB::table($tableName)->get()->toArray();

                    $backup['tables'][$tableName] = [
                        'structure' => $createStatement,
                        'data' => $data,
                        'count' => count($data)
                    ];
                }
            } else {
                // For MySQL
                $tables = DB::select('SHOW TABLES');
                $tableKey = 'Tables_in_' . $database;

                foreach ($tables as $table) {
                    $tableName = $table->$tableKey;

                    // Get table structure
                    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                    $createStatement = $createTable->{'Create Table'} ?? $createTable->{'Create View'} ?? '';

                    // Get table data
                    $data = DB::table($tableName)->get()->toArray();

                    $backup['tables'][$tableName] = [
                        'structure' => $createStatement,
                        'data' => $data,
                        'count' => count($data)
                    ];
                }
            }

            // Convert to JSON
            $json = json_encode($backup, JSON_PRETTY_PRINT);

            // Generate filename
            $filename = 'backup-pazario-' . date('Y-m-d-His') . '.json';

            // Store last backup date
            Setting::set('last_backup_date', now()->toDateTimeString(), 'string', 'database');

            // Return download response
            return response()->streamDownload(function() use ($json) {
                echo $json;
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);

        } catch (\Exception $e) {
            session()->flash('error', 'Greška pri kreiranju backup-a: ' . $e->getMessage());
        }
    }

    public function importDatabase()
    {
        $this->validate([
            'backupFile' => 'required|file|mimes:json,sql|max:51200', // Max 50MB
        ]);

        try {
            // Read the uploaded file
            $content = file_get_contents($this->backupFile->getRealPath());

            // Check if it's JSON or SQL
            if ($this->backupFile->getClientOriginalExtension() === 'json') {
                $this->restoreFromJson($content);
            } else {
                $this->restoreFromSql($content);
            }

            // Clear the file
            $this->backupFile = null;
            $this->showRestoreConfirmation = false;

            session()->flash('success', 'Backup je uspešno vraćen! Molimo vas da se ponovo ulogujete.');

            // Clear all sessions and cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');

            // Redirect to login after a delay
            $this->dispatch('redirect-after-restore');

        } catch (\Exception $e) {
            $this->showRestoreConfirmation = false;
            session()->flash('error', 'Greška pri vraćanju backup-a: ' . $e->getMessage());
        }
    }

    private function restoreFromJson($content)
    {
        $backup = json_decode($content, true);

        if (!$backup || !isset($backup['tables'])) {
            throw new \Exception('Neispravan format backup fajla.');
        }

        DB::beginTransaction();

        try {
            // Disable foreign key checks based on database type
            $connection = config('database.default');

            if ($connection === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
            }

            // Drop and recreate all tables
            foreach ($backup['tables'] as $tableName => $tableData) {
                // Drop existing table
                if ($connection === 'sqlite') {
                    DB::statement("DROP TABLE IF EXISTS {$tableName}");
                } else {
                    DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
                }

                // Create table structure
                if (isset($tableData['structure']) && !empty($tableData['structure'])) {
                    DB::statement($tableData['structure']);
                }

                // Insert data
                if (isset($tableData['data']) && !empty($tableData['data'])) {
                    foreach (array_chunk($tableData['data'], 100) as $chunk) {
                        // Convert objects to arrays
                        $insertData = array_map(function($item) {
                            return (array) $item;
                        }, $chunk);

                        DB::table($tableName)->insert($insertData);
                    }
                }
            }

            // Re-enable foreign key checks
            if ($connection === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            throw $e;
        }
    }

    private function restoreFromSql($content)
    {
        // Execute SQL commands
        DB::unprepared($content);
    }

    private function loadDatabaseInfo()
    {
        try {
            // Get database type
            $this->databaseType = ucfirst(config('database.default'));

            // Get database size
            $connection = config('database.default');
            $database = config("database.connections.{$connection}.database");

            if ($this->databaseType === 'Mysql') {
                $size = DB::select("
                    SELECT
                        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                    FROM information_schema.tables
                    WHERE table_schema = ?
                ", [$database])[0]->size_mb ?? 0;

                $this->databaseSize = $size . ' MB';
            } else {
                // For SQLite
                $dbPath = config("database.connections.{$connection}.database");
                if (file_exists($dbPath)) {
                    $this->databaseSize = round(filesize($dbPath) / 1024 / 1024, 2) . ' MB';
                }
            }

            // Get table count
            if ($this->databaseType === 'Sqlite') {
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            } else {
                $tables = DB::select('SHOW TABLES');
            }
            $this->tableCount = count($tables);

            // Get last backup date
            $this->lastBackupDate = Setting::get('last_backup_date');

        } catch (\Exception $e) {
            $this->databaseType = 'Nepoznat';
            $this->databaseSize = 'Nepoznato';
            $this->tableCount = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.admin');
    }
}
