<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class Settings extends Component
{
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
    
    // Promotion Settings
    public $promotionFeaturedCategoryPrice;
    public $promotionFeaturedCategoryDays;
    public $promotionFeaturedHomepagePrice;
    public $promotionFeaturedHomepageDays;
    public $promotionHighlightedPrice;
    public $promotionHighlightedDays;
    public $promotionAutoRefreshPrice;
    public $promotionAutoRefreshDays;
    public $promotionLargeImagePrice;
    public $promotionLargeImageDays;
    public $promotionExtendedDurationPrice;
    public $promotionExtendedDurationDays;
    
    // Email Settings
    public $adminEmail;
    public $supportEmail;
    
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
        // Promotion validation
        'promotionFeaturedCategoryPrice' => 'required|integer|min:1|max:10000',
        'promotionFeaturedCategoryDays' => 'required|integer|min:1|max:365',
        'promotionFeaturedHomepagePrice' => 'required|integer|min:1|max:10000',
        'promotionFeaturedHomepageDays' => 'required|integer|min:1|max:365',
        'promotionHighlightedPrice' => 'required|integer|min:1|max:10000',
        'promotionHighlightedDays' => 'required|integer|min:1|max:365',
        'promotionAutoRefreshPrice' => 'required|integer|min:1|max:10000',
        'promotionAutoRefreshDays' => 'required|integer|min:1|max:365',
        'promotionLargeImagePrice' => 'required|integer|min:1|max:10000',
        'promotionLargeImageDays' => 'required|integer|min:1|max:365',
        'promotionExtendedDurationPrice' => 'required|integer|min:1|max:10000',
        'promotionExtendedDurationDays' => 'required|integer|min:1|max:365',
        'adminEmail' => 'required|email|max:255',
        'supportEmail' => 'required|email|max:255',
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
        $this->siteName = Setting::get('site_name', 'MyOglasi');
        $this->maxImagesPerListing = Setting::get('max_images_per_listing', 10);
        $this->listingAutoExpireDays = Setting::get('listing_auto_expire_days', 60);
        $this->maintenanceMode = Setting::get('maintenance_mode', false);
        $this->monthlyListingLimit = Setting::get('monthly_listing_limit', 50);
        $this->minimumCreditTransfer = Setting::get('minimum_credit_transfer', 10);
        $this->showLastSeen = Setting::get('show_last_seen', true);
        
        // Promotion Settings
        $this->promotionFeaturedCategoryPrice = Setting::get('promotion_featured_category_price', 100);
        $this->promotionFeaturedCategoryDays = Setting::get('promotion_featured_category_days', 7);
        $this->promotionFeaturedHomepagePrice = Setting::get('promotion_featured_homepage_price', 200);
        $this->promotionFeaturedHomepageDays = Setting::get('promotion_featured_homepage_days', 3);
        $this->promotionHighlightedPrice = Setting::get('promotion_highlighted_price', 50);
        $this->promotionHighlightedDays = Setting::get('promotion_highlighted_days', 14);
        $this->promotionAutoRefreshPrice = Setting::get('promotion_auto_refresh_price', 80);
        $this->promotionAutoRefreshDays = Setting::get('promotion_auto_refresh_days', 30);
        $this->promotionLargeImagePrice = Setting::get('promotion_large_image_price', 30);
        $this->promotionLargeImageDays = Setting::get('promotion_large_image_days', 14);
        $this->promotionExtendedDurationPrice = Setting::get('promotion_extended_duration_price', 60);
        $this->promotionExtendedDurationDays = Setting::get('promotion_extended_duration_days', 30);
        
        // Email Settings
        $this->adminEmail = Setting::get('admin_email', 'admin@myoglasi.rs');
        $this->supportEmail = Setting::get('support_email', 'support@myoglasi.rs');
        
        // Banking Settings
        $this->bankAccountNumber = Setting::get('bank_account_number', '265-0000000003456-78');
        $this->bankName = Setting::get('bank_name', 'Intesa Banka a.d. Beograd');
        $this->companyName = Setting::get('company_name', 'MyOglasi d.o.o.');
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
            'promotionFeaturedCategoryPrice' => 'required|integer|min:1|max:10000',
            'promotionFeaturedCategoryDays' => 'required|integer|min:1|max:365',
            'promotionFeaturedHomepagePrice' => 'required|integer|min:1|max:10000',
            'promotionFeaturedHomepageDays' => 'required|integer|min:1|max:365',
            'promotionHighlightedPrice' => 'required|integer|min:1|max:10000',
            'promotionHighlightedDays' => 'required|integer|min:1|max:365',
            'promotionAutoRefreshPrice' => 'required|integer|min:1|max:10000',
            'promotionAutoRefreshDays' => 'required|integer|min:1|max:365',
            'promotionLargeImagePrice' => 'required|integer|min:1|max:10000',
            'promotionLargeImageDays' => 'required|integer|min:1|max:365',
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
        
        // Save promotion settings
        Setting::set('promotion_featured_category_price', $this->promotionFeaturedCategoryPrice, 'integer', 'promotions');
        Setting::set('promotion_featured_category_days', $this->promotionFeaturedCategoryDays, 'integer', 'promotions');
        Setting::set('promotion_featured_homepage_price', $this->promotionFeaturedHomepagePrice, 'integer', 'promotions');
        Setting::set('promotion_featured_homepage_days', $this->promotionFeaturedHomepageDays, 'integer', 'promotions');
        Setting::set('promotion_highlighted_price', $this->promotionHighlightedPrice, 'integer', 'promotions');
        Setting::set('promotion_highlighted_days', $this->promotionHighlightedDays, 'integer', 'promotions');
        Setting::set('promotion_auto_refresh_price', $this->promotionAutoRefreshPrice, 'integer', 'promotions');
        Setting::set('promotion_auto_refresh_days', $this->promotionAutoRefreshDays, 'integer', 'promotions');
        Setting::set('promotion_large_image_price', $this->promotionLargeImagePrice, 'integer', 'promotions');
        Setting::set('promotion_large_image_days', $this->promotionLargeImageDays, 'integer', 'promotions');
        Setting::set('promotion_extended_duration_price', $this->promotionExtendedDurationPrice, 'integer', 'promotions');
        Setting::set('promotion_extended_duration_days', $this->promotionExtendedDurationDays, 'integer', 'promotions');

        session()->flash('success', 'Opšta podešavanja su uspešno sačuvana.');
    }

    public function saveEmailSettings()
    {
        $this->validate([
            'adminEmail' => 'required|email|max:255',
            'supportEmail' => 'required|email|max:255',
        ]);

        Setting::set('admin_email', $this->adminEmail, 'string', 'email');
        Setting::set('support_email', $this->supportEmail, 'string', 'email');

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

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.admin');
    }
}
