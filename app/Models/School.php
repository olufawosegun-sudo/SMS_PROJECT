<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid', 'name', 'slug', 'subdomain', 'school_code', 'email', 'phone', 'website',
        'address', 'address_line_1', 'address_line_2', 'address_line_3', 'city', 'state', 'country',
        'currency', 'currency_symbol', 'logo', 'motto',
        'primary_color', 'secondary_color', 'portal_theme', 'hero_banner', 'welcome_title', 'welcome_message',
        'social_facebook', 'social_instagram', 'social_twitter', 'social_linkedin', 'portal_settings',
        'admission_status', 'about_text', 'portal_hero_title', 'portal_hero_subtitle',
        'educational_system', 'active_stages', 'term_system', 'pass_mark',
        'bank_name', 'account_number', 'account_name', 'bank_branch', 'bank_sort_code',
        'momo_number', 'momo_network', 'payment_instructions',
    ];

    protected $casts = [
        'active_stages' => 'array',
        'portal_settings' => 'array',
    ];

    /**
     * Comprehensive Country to Official Currency Map
     */
    public static function getCountryCurrencyMap(): array
    {
        return [
            'Nigeria' => ['code' => 'NGN', 'symbol' => '₦', 'name' => 'Nigerian Naira (₦)', 'flag' => '🇳🇬'],
            'Ghana' => ['code' => 'GHS', 'symbol' => 'GH₵', 'name' => 'Ghanaian Cedi (GH₵)', 'flag' => '🇬🇭'],
            'Sierra Leone' => ['code' => 'SLE', 'symbol' => 'Le', 'name' => 'Sierra Leonean Leone (Le)', 'flag' => '🇸🇱'],
            'Liberia' => ['code' => 'LRD', 'symbol' => 'L$', 'name' => 'Liberian Dollar (L$)', 'flag' => '🇱🇷'],
            'The Gambia' => ['code' => 'GMD', 'symbol' => 'D', 'name' => 'Gambian Dalasi (D)', 'flag' => '🇬🇲'],
            'Kenya' => ['code' => 'KES', 'symbol' => 'KSh', 'name' => 'Kenyan Shilling (KSh)', 'flag' => '🇰🇪'],
            'South Africa' => ['code' => 'ZAR', 'symbol' => 'R', 'name' => 'South African Rand (R)', 'flag' => '🇿🇦'],
            'Rwanda' => ['code' => 'RWF', 'symbol' => 'FRw', 'name' => 'Rwandan Franc (FRw)', 'flag' => '🇷🇼'],
            'Uganda' => ['code' => 'UGX', 'symbol' => 'USh', 'name' => 'Ugandan Shilling (USh)', 'flag' => '🇺🇬'],
            'Tanzania' => ['code' => 'TZS', 'symbol' => 'TSh', 'name' => 'Tanzanian Shilling (TSh)', 'flag' => '🇹🇿'],
            'Cameroon' => ['code' => 'XAF', 'symbol' => 'FCFA', 'name' => 'Central African CFA Franc (FCFA)', 'flag' => '🇨🇲'],
            'Ivory Coast' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇨🇮'],
            'Senegal' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇸🇳'],
            'Benin' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇧🇯'],
            'Togo' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇹🇬'],
            'Burkina Faso' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇧🇫'],
            'Mali' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇲🇱'],
            'Niger' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇳🇪'],
            'Guinea' => ['code' => 'GNF', 'symbol' => 'FG', 'name' => 'Guinean Franc (FG)', 'flag' => '🇬🇳'],
            'United Kingdom' => ['code' => 'GBP', 'symbol' => '£', 'name' => 'British Pound (£)', 'flag' => '🇬🇧'],
            'United States' => ['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar ($)', 'flag' => '🇺🇸'],
            'Canada' => ['code' => 'CAD', 'symbol' => 'CA$', 'name' => 'Canadian Dollar (CA$)', 'flag' => '🇨🇦'],
            'Australia' => ['code' => 'AUD', 'symbol' => 'A$', 'name' => 'Australian Dollar (A$)', 'flag' => '🇦🇺'],
            'Eurozone' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro (€)', 'flag' => '🇪🇺'],
            'United Arab Emirates' => ['code' => 'AED', 'symbol' => 'AED', 'name' => 'UAE Dirham (AED)', 'flag' => '🇦🇪'],
            'India' => ['code' => 'INR', 'symbol' => '₹', 'name' => 'Indian Rupee (₹)', 'flag' => '🇮🇳'],
            'China' => ['code' => 'CNY', 'symbol' => '¥', 'name' => 'Chinese Yuan (¥)', 'flag' => '🇨🇳'],
            'Japan' => ['code' => 'JPY', 'symbol' => '¥', 'name' => 'Japanese Yen (¥)', 'flag' => '🇯🇵'],
        ];
    }

    /**
     * Resolve currency info based on Country name
     */
    public static function getCurrencyForCountry(?string $country): array
    {
        if (empty($country)) {
            return ['code' => 'NGN', 'symbol' => '₦', 'name' => 'Nigerian Naira (₦)', 'flag' => '🇳🇬'];
        }

        $normalized = strtolower(trim($country));
        $normalizedMap = [
            'nigeria' => ['code' => 'NGN', 'symbol' => '₦', 'name' => 'Nigerian Naira (₦)', 'flag' => '🇳🇬'],
            'ghana' => ['code' => 'GHS', 'symbol' => 'GH₵', 'name' => 'Ghanaian Cedi (GH₵)', 'flag' => '🇬🇭'],
            'sierra leone' => ['code' => 'SLE', 'symbol' => 'Le', 'name' => 'Sierra Leonean Leone (Le)', 'flag' => '🇸🇱'],
            'liberia' => ['code' => 'LRD', 'symbol' => 'L$', 'name' => 'Liberian Dollar (L$)', 'flag' => '🇱🇷'],
            'the gambia' => ['code' => 'GMD', 'symbol' => 'D', 'name' => 'Gambian Dalasi (D)', 'flag' => '🇬🇲'],
            'gambia' => ['code' => 'GMD', 'symbol' => 'D', 'name' => 'Gambian Dalasi (D)', 'flag' => '🇬🇲'],
            'kenya' => ['code' => 'KES', 'symbol' => 'KSh', 'name' => 'Kenyan Shilling (KSh)', 'flag' => '🇰🇪'],
            'south africa' => ['code' => 'ZAR', 'symbol' => 'R', 'name' => 'South African Rand (R)', 'flag' => '🇿🇦'],
            'rwanda' => ['code' => 'RWF', 'symbol' => 'FRw', 'name' => 'Rwandan Franc (FRw)', 'flag' => '🇷🇼'],
            'uganda' => ['code' => 'UGX', 'symbol' => 'USh', 'name' => 'Ugandan Shilling (USh)', 'flag' => '🇺🇬'],
            'tanzania' => ['code' => 'TZS', 'symbol' => 'TSh', 'name' => 'Tanzanian Shilling (TSh)', 'flag' => '🇹🇿'],
            'cameroon' => ['code' => 'XAF', 'symbol' => 'FCFA', 'name' => 'Central African CFA Franc (FCFA)', 'flag' => '🇨🇲'],
            'ivory coast' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇨🇮'],
            'côte d\'ivoire' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇨🇮'],
            'senegal' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇸🇳'],
            'benin' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇧🇯'],
            'togo' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇹🇬'],
            'burkina faso' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇧🇫'],
            'mali' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇲🇱'],
            'niger' => ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA Franc (CFA)', 'flag' => '🇳🇪'],
            'guinea' => ['code' => 'GNF', 'symbol' => 'FG', 'name' => 'Guinean Franc (FG)', 'flag' => '🇬🇳'],
            'united kingdom' => ['code' => 'GBP', 'symbol' => '£', 'name' => 'British Pound (£)', 'flag' => '🇬🇧'],
            'uk' => ['code' => 'GBP', 'symbol' => '£', 'name' => 'British Pound (£)', 'flag' => '🇬🇧'],
            'united states' => ['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar ($)', 'flag' => '🇺🇸'],
            'usa' => ['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar ($)', 'flag' => '🇺🇸'],
            'us' => ['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar ($)', 'flag' => '🇺🇸'],
            'canada' => ['code' => 'CAD', 'symbol' => 'CA$', 'name' => 'Canadian Dollar (CA$)', 'flag' => '🇨🇦'],
            'australia' => ['code' => 'AUD', 'symbol' => 'A$', 'name' => 'Australian Dollar (A$)', 'flag' => '🇦🇺'],
            'eurozone' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro (€)', 'flag' => '🇪🇺'],
            'united arab emirates' => ['code' => 'AED', 'symbol' => 'AED', 'name' => 'UAE Dirham (AED)', 'flag' => '🇦🇪'],
            'uae' => ['code' => 'AED', 'symbol' => 'AED', 'name' => 'UAE Dirham (AED)', 'flag' => '🇦🇪'],
            'india' => ['code' => 'INR', 'symbol' => '₹', 'name' => 'Indian Rupee (₹)', 'flag' => '🇮🇳'],
            'china' => ['code' => 'CNY', 'symbol' => '¥', 'name' => 'Chinese Yuan (¥)', 'flag' => '🇨🇳'],
            'japan' => ['code' => 'JPY', 'symbol' => '¥', 'name' => 'Japanese Yen (¥)', 'flag' => '🇯🇵'],
        ];

        return $normalizedMap[$normalized] ?? ['code' => 'NGN', 'symbol' => '₦', 'name' => 'Nigerian Naira (₦)', 'flag' => '🇳🇬'];
    }

    /**
     * Get the dynamic currency symbol derived from country or custom set symbol
     */
    public function getCurrencySymbolAttribute(): string
    {
        if (! empty($this->attributes['currency_symbol'])) {
            return $this->attributes['currency_symbol'];
        }

        return self::getCurrencyForCountry($this->country ?? null)['symbol'];
    }

    /**
     * Get the dynamic currency code derived from country or custom set code
     */
    public function getCurrencyCodeAttribute(): string
    {
        if (! empty($this->attributes['currency'])) {
            return $this->attributes['currency'];
        }

        return self::getCurrencyForCountry($this->country ?? null)['code'];
    }

    /**
     * Get the dynamic currency full name derived from country
     */
    public function getCurrencyNameAttribute(): string
    {
        return self::getCurrencyForCountry($this->country ?? null)['name'];
    }

    /**
     * Get Country Standard Banks & Mobile Money channels
     */
    public static function getCountryBankingMap(): array
    {
        return [
            'Ghana' => [
                'banks' => ['GCB Bank', 'Ecobank Ghana', 'Stanbic Bank Ghana', 'Absa Bank Ghana', 'Fidelity Bank Ghana', 'CalBank', 'Zenith Bank Ghana', 'Access Bank Ghana'],
                'default_bank' => 'GCB Bank',
                'momo_networks' => ['MTN MoMo', 'Telecel Cash', 'AT Money'],
                'account_label' => 'Account Number',
                'sort_label' => 'Branch / Sort Code',
            ],
            'Nigeria' => [
                'banks' => ['Guaranty Trust Bank (GTBank)', 'Zenith Bank', 'Access Bank', 'First Bank of Nigeria', 'United Bank for Africa (UBA)', 'Stanbic IBTC', 'Fidelity Bank', 'Kuda Bank', 'Opay'],
                'default_bank' => 'Guaranty Trust Bank (GTBank)',
                'momo_networks' => ['Opay', 'Palmpay', 'Moniepoint'],
                'account_label' => 'NUBAN Account Number',
                'sort_label' => 'Sort Code',
            ],
            'Kenya' => [
                'banks' => ['KCB Bank Kenya', 'Equity Bank Kenya', 'Co-operative Bank of Kenya', 'Standard Chartered Kenya', 'NCBA Bank', 'Absa Bank Kenya'],
                'default_bank' => 'KCB Bank Kenya',
                'momo_networks' => ['M-Pesa (Safaricom)', 'Airtel Money'],
                'account_label' => 'Account / Paybill Number',
                'sort_label' => 'Branch Code',
            ],
            'Sierra Leone' => [
                'banks' => ['Sierra Leone Commercial Bank (SLCB)', 'Rokel Commercial Bank', 'Ecobank Sierra Leone', 'Standard Chartered Sierra Leone', 'Union Trust Bank'],
                'default_bank' => 'Sierra Leone Commercial Bank (SLCB)',
                'momo_networks' => ['Orange Money', 'Africell Money'],
                'account_label' => 'BBAN Account Number',
                'sort_label' => 'Branch',
            ],
            'Liberia' => [
                'banks' => ['Ecobank Liberia', 'International Bank Liberia (IBL)', 'United Bank for Africa Liberia', 'Global Bank Liberia', 'Liberian Bank for Development and Investment (LBDI)'],
                'default_bank' => 'Ecobank Liberia',
                'momo_networks' => ['Lonestar Cell MTN Mobile Money', 'Orange Money Liberia'],
                'account_label' => 'Account Number',
                'sort_label' => 'Branch',
            ],
            'The Gambia' => [
                'banks' => ['Standard Chartered Bank Gambia', 'Trust Bank Gambia', 'Ecobank Gambia', 'Bloom Bank Africa', 'FBNBank Gambia'],
                'default_bank' => 'Trust Bank Gambia',
                'momo_networks' => ['QMoney', 'AfriMoney'],
                'account_label' => 'Account Number',
                'sort_label' => 'Branch',
            ],
            'South Africa' => [
                'banks' => ['Standard Bank', 'First National Bank (FNB)', 'Absa Bank', 'Nedbank', 'Capitec Bank'],
                'default_bank' => 'First National Bank (FNB)',
                'momo_networks' => [],
                'account_label' => 'Account Number',
                'sort_label' => 'Branch Code',
            ],
            'United Kingdom' => [
                'banks' => ['Barclays Bank', 'HSBC UK', 'NatWest', 'Lloyds Bank', 'Santander UK', 'Standard Chartered'],
                'default_bank' => 'Barclays Bank',
                'momo_networks' => [],
                'account_label' => 'Account Number / IBAN',
                'sort_label' => 'Sort Code',
            ],
            'United States' => [
                'banks' => ['JPMorgan Chase', 'Bank of America', 'Wells Fargo', 'Citibank', 'PNC Bank'],
                'default_bank' => 'JPMorgan Chase',
                'momo_networks' => ['Zelle', 'Venmo'],
                'account_label' => 'Checking Account Number',
                'sort_label' => 'Routing Number (ABA)',
            ],
            'Canada' => [
                'banks' => ['Royal Bank of Canada (RBC)', 'TD Bank', 'Scotiabank', 'BMO Bank of Montreal', 'CIBC'],
                'default_bank' => 'Royal Bank of Canada (RBC)',
                'momo_networks' => ['Interac e-Transfer'],
                'account_label' => 'Account Number',
                'sort_label' => 'Transit & Institution Number',
            ],
        ];
    }

    /**
     * Get resolved banking info matching the school's configured bank or country default
     */
    public function getResolvedBankingDetailsAttribute(): array
    {
        $country = $this->country ?? 'Nigeria';
        $bankingMap = self::getCountryBankingMap();
        $countryConfig = $bankingMap[$country] ?? ($bankingMap['Nigeria'] ?? []);

        $bankName = ! empty($this->attributes['bank_name'])
            ? $this->attributes['bank_name']
            : ($countryConfig['default_bank'] ?? 'Commercial Bank');

        $accountName = ! empty($this->attributes['account_name'])
            ? $this->attributes['account_name']
            : $this->name;

        $accountNumber = ! empty($this->attributes['account_number'])
            ? $this->attributes['account_number']
            : null;

        $momoNumber = ! empty($this->attributes['momo_number']) ? $this->attributes['momo_number'] : null;
        $momoNetwork = ! empty($this->attributes['momo_network'])
            ? $this->attributes['momo_network']
            : ($countryConfig['momo_networks'][0] ?? null);

        return [
            'country' => $country,
            'bank_name' => $bankName,
            'account_name' => $accountName,
            'account_number' => $accountNumber,
            'bank_branch' => $this->attributes['bank_branch'] ?? null,
            'bank_sort_code' => $this->attributes['bank_sort_code'] ?? null,
            'account_label' => $countryConfig['account_label'] ?? 'Account Number',
            'sort_label' => $countryConfig['sort_label'] ?? 'Sort / Branch Code',
            'has_momo' => ! empty($countryConfig['momo_networks']),
            'momo_networks' => $countryConfig['momo_networks'] ?? [],
            'momo_number' => $momoNumber,
            'momo_network' => $momoNetwork,
            'instructions' => $this->attributes['payment_instructions'] ?? null,
            'available_country_banks' => $countryConfig['banks'] ?? [],
        ];
    }

    /**
     * West African Secondary School system templates/presets (WAEC, WASSCE, NECO, BECE, GES)
     */
    public static function getSystemPresets(): array
    {
        return [
            'nigerian_waec_standard' => [
                'name' => 'Nigerian WAEC / NECO Secondary System (JSS 1-3 & SSS 1-3)',
                'description' => 'Standard 6-year Nigerian secondary system preparing students for BECE (Junior) and WASSCE / NECO SSCE (Senior).',
                'term_system' => '3_terms',
                'stages' => ['junior_secondary', 'senior_secondary'],
                'classes' => [
                    ['name' => 'JSS 1 (Basic 7)', 'level' => 'JSS1', 'category' => 'junior_secondary', 'order' => 1],
                    ['name' => 'JSS 2 (Basic 8)', 'level' => 'JSS2', 'category' => 'junior_secondary', 'order' => 2],
                    ['name' => 'JSS 3 (Basic 9)', 'level' => 'JSS3', 'category' => 'junior_secondary', 'order' => 3],
                    ['name' => 'SSS 1 (Senior Sec 1)', 'level' => 'SS1', 'category' => 'senior_secondary', 'order' => 4],
                    ['name' => 'SSS 2 (Senior Sec 2)', 'level' => 'SS2', 'category' => 'senior_secondary', 'order' => 5],
                    ['name' => 'SSS 3 (Senior Sec 3)', 'level' => 'SS3', 'category' => 'senior_secondary', 'order' => 6],
                ],
            ],
            'waec_departmental_streams' => [
                'name' => 'WAEC Departmentalized Secondary (Science, Arts, Commercial & Technical)',
                'description' => 'Junior Secondary (JSS 1-3) + Senior Secondary grouped by WAEC subject departments (Science, Arts, Commercial & Technical).',
                'term_system' => '3_terms',
                'stages' => ['junior_secondary', 'senior_secondary'],
                'classes' => [
                    ['name' => 'JSS 1', 'level' => 'JSS1', 'category' => 'junior_secondary', 'order' => 1],
                    ['name' => 'JSS 2', 'level' => 'JSS2', 'category' => 'junior_secondary', 'order' => 2],
                    ['name' => 'JSS 3', 'level' => 'JSS3', 'category' => 'junior_secondary', 'order' => 3],
                    ['name' => 'SSS 1 Science', 'level' => 'SS1', 'category' => 'senior_secondary', 'order' => 4],
                    ['name' => 'SSS 1 Arts & Humanities', 'level' => 'SS1', 'category' => 'senior_secondary', 'order' => 5],
                    ['name' => 'SSS 1 Commercial / Business', 'level' => 'SS1', 'category' => 'senior_secondary', 'order' => 6],
                    ['name' => 'SSS 1 Technical / Vocational', 'level' => 'SS1', 'category' => 'senior_secondary', 'order' => 7],
                    ['name' => 'SSS 2 Science', 'level' => 'SS2', 'category' => 'senior_secondary', 'order' => 8],
                    ['name' => 'SSS 2 Arts & Humanities', 'level' => 'SS2', 'category' => 'senior_secondary', 'order' => 9],
                    ['name' => 'SSS 2 Commercial / Business', 'level' => 'SS2', 'category' => 'senior_secondary', 'order' => 10],
                    ['name' => 'SSS 2 Technical / Vocational', 'level' => 'SS2', 'category' => 'senior_secondary', 'order' => 11],
                    ['name' => 'SSS 3 Science', 'level' => 'SS3', 'category' => 'senior_secondary', 'order' => 12],
                    ['name' => 'SSS 3 Arts & Humanities', 'level' => 'SS3', 'category' => 'senior_secondary', 'order' => 13],
                    ['name' => 'SSS 3 Commercial / Business', 'level' => 'SS3', 'category' => 'senior_secondary', 'order' => 14],
                    ['name' => 'SSS 3 Technical / Vocational', 'level' => 'SS3', 'category' => 'senior_secondary', 'order' => 15],
                ],
            ],
            'ghana_ges_wassce' => [
                'name' => 'Ghanaian Secondary System (JHS 1-3 & SHS 1-3 Programs)',
                'description' => 'Ghana Education Service structure: Junior High School (JHS 1-3) for BECE and Senior High School (SHS 1-3) WASSCE programs.',
                'term_system' => '3_terms',
                'stages' => ['junior_secondary', 'senior_secondary'],
                'classes' => [
                    ['name' => 'JHS 1 (Basic 7)', 'level' => 'JHS1', 'category' => 'junior_secondary', 'order' => 1],
                    ['name' => 'JHS 2 (Basic 8)', 'level' => 'JHS2', 'category' => 'junior_secondary', 'order' => 2],
                    ['name' => 'JHS 3 (Basic 9 / BECE)', 'level' => 'JHS3', 'category' => 'junior_secondary', 'order' => 3],
                    ['name' => 'SHS 1 (General Science)', 'level' => 'SHS1', 'category' => 'senior_secondary', 'order' => 4],
                    ['name' => 'SHS 1 (General Arts)', 'level' => 'SHS1', 'category' => 'senior_secondary', 'order' => 5],
                    ['name' => 'SHS 1 (Business)', 'level' => 'SHS1', 'category' => 'senior_secondary', 'order' => 6],
                    ['name' => 'SHS 2 (General Science)', 'level' => 'SHS2', 'category' => 'senior_secondary', 'order' => 7],
                    ['name' => 'SHS 2 (General Arts)', 'level' => 'SHS2', 'category' => 'senior_secondary', 'order' => 8],
                    ['name' => 'SHS 2 (Business)', 'level' => 'SHS2', 'category' => 'senior_secondary', 'order' => 9],
                    ['name' => 'SHS 3 (General Science)', 'level' => 'SHS3', 'category' => 'senior_secondary', 'order' => 10],
                    ['name' => 'SHS 3 (General Arts)', 'level' => 'SHS3', 'category' => 'senior_secondary', 'order' => 11],
                    ['name' => 'SHS 3 (Business)', 'level' => 'SHS3', 'category' => 'senior_secondary', 'order' => 12],
                ],
            ],
            'sierra_leone_gambia' => [
                'name' => 'Sierra Leone, Gambia & Liberia WAEC Secondary (JSS 1-3 & SSS 1-3)',
                'description' => 'West African examinations framework for Sierra Leone, The Gambia, and Liberia (JSS 1-3 / Forms 1-3 and SSS 1-3 / Forms 4-6).',
                'term_system' => '3_terms',
                'stages' => ['junior_secondary', 'senior_secondary'],
                'classes' => [
                    ['name' => 'JSS 1 (Form 1)', 'level' => 'JSS1', 'category' => 'junior_secondary', 'order' => 1],
                    ['name' => 'JSS 2 (Form 2)', 'level' => 'JSS2', 'category' => 'junior_secondary', 'order' => 2],
                    ['name' => 'JSS 3 (Form 3 / BECE)', 'level' => 'JSS3', 'category' => 'junior_secondary', 'order' => 3],
                    ['name' => 'SSS 1 (Form 4)', 'level' => 'SS1', 'category' => 'senior_secondary', 'order' => 4],
                    ['name' => 'SSS 2 (Form 5)', 'level' => 'SS2', 'category' => 'senior_secondary', 'order' => 5],
                    ['name' => 'SSS 3 (Form 6 / WASSCE)', 'level' => 'SS3', 'category' => 'senior_secondary', 'order' => 6],
                ],
            ],
            'waec_senior_college_only' => [
                'name' => 'Senior Secondary / WASSCE & NECO College Only (SSS 1 - 3)',
                'description' => 'Specialized Senior Secondary / High School preparing students exclusively for WASSCE, NECO SSCE, and NABTEB.',
                'term_system' => '3_terms',
                'stages' => ['senior_secondary'],
                'classes' => [
                    ['name' => 'SSS 1', 'level' => 'SS1', 'category' => 'senior_secondary', 'order' => 1],
                    ['name' => 'SSS 2', 'level' => 'SS2', 'category' => 'senior_secondary', 'order' => 2],
                    ['name' => 'SSS 3 (WASSCE Candidate Class)', 'level' => 'SS3', 'category' => 'senior_secondary', 'order' => 3],
                ],
            ],
            'waec_junior_secondary_only' => [
                'name' => 'Junior Secondary / BECE College Only (JSS 1 - 3)',
                'description' => 'Specialized Junior Secondary / Basic 7-9 preparing students for National & State BECE examinations.',
                'term_system' => '3_terms',
                'stages' => ['junior_secondary'],
                'classes' => [
                    ['name' => 'JSS 1 (Basic 7)', 'level' => 'JSS1', 'category' => 'junior_secondary', 'order' => 1],
                    ['name' => 'JSS 2 (Basic 8)', 'level' => 'JSS2', 'category' => 'junior_secondary', 'order' => 2],
                    ['name' => 'JSS 3 (BECE Candidate Class)', 'level' => 'JSS3', 'category' => 'junior_secondary', 'order' => 3],
                ],
            ],
            'custom_west_african' => [
                'name' => 'Custom West African Secondary Structure',
                'description' => 'Fully custom West African secondary school class names, departmental tracks, and arm allocations.',
                'term_system' => '3_terms',
                'stages' => ['junior_secondary', 'senior_secondary'],
                'classes' => [],
            ],
        ];
    }

    public function getPublicUrlAttribute(): string
    {
        $slug = $this->slug ?: Str::slug($this->name);

        return url('/school/'.$slug);
    }

    public function getApplyUrlAttribute(): string
    {
        $slug = $this->slug ?: Str::slug($this->name);

        return url('/school/'.$slug.'/apply');
    }

    public function getCareersUrlAttribute(): string
    {
        $slug = $this->slug ?: Str::slug($this->name);

        return url('/school/'.$slug.'/careers');
    }

    public function jobApplications()
    {
        return $this->hasMany(StaffJobApplication::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function branches()
    {
        return $this->hasMany(SchoolBranch::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function sessions()
    {
        return $this->hasMany(AcademicSession::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }
}
