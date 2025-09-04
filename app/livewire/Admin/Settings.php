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
        $this->listingAutoExpireDays = Setting::get('listing_auto_expire_days', 30);
        $this->maintenanceMode = Setting::get('maintenance_mode', false);
        
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
        ]);

        Setting::set('site_name', $this->siteName, 'string', 'general');
        Setting::set('max_images_per_listing', $this->maxImagesPerListing, 'integer', 'general');
        Setting::set('listing_auto_expire_days', $this->listingAutoExpireDays, 'integer', 'general');
        Setting::set('maintenance_mode', $this->maintenanceMode, 'boolean', 'general');

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

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.admin');
    }
}
