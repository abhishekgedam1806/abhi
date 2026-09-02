<?php

namespace App\Services\Localization;

use App\Language;
use Illuminate\Support\Collection;

class LanguageSeoService
{
    /**
     * Generate localized Page Title based on keyword, location, and active locale
     *
     * @param string $keyword
     * @param string|null $cityName
     * @param string $locale
     * @param string|null $siteName
     * @return string
     */
    public function getLocalizedTitle(string $keyword, ?string $cityName = null, string $locale = 'en', ?string $siteName = null): string
    {
        $siteName = $siteName ?: config('app.name', 'Job Portal');
        $keyword = trim($keyword);
        $cityName = $cityName ? trim($cityName) : '';

        if (empty($cityName)) {
            switch ($locale) {
                case 'hi':
                    return "{$keyword} नौकरियां | {$siteName}";
                case 'fr':
                    return "Emplois {$keyword} | {$siteName}";
                case 'de':
                    return "{$keyword} Jobs & Stellenangebote | {$siteName}";
                case 'es':
                    return "Empleos de {$keyword} | {$siteName}";
                case 'ar':
                    return "وظائف {$keyword} | {$siteName}";
                case 'mr':
                    return "{$keyword} नोकऱ्या | {$siteName}";
                case 'bn':
                    return "{$keyword} চাকরি | {$siteName}";
                case 'ta':
                    return "{$keyword} வேலைகள் | {$siteName}";
                case 'te':
                    return "{$keyword} ఉద్యోగాలు | {$siteName}";
                case 'gu':
                    return "{$keyword} નોકરીઓ | {$siteName}";
                case 'pt':
                    return "Vagas de {$keyword} | {$siteName}";
                case 'ru':
                    return "Вакансии {$keyword} | {$siteName}";
                default:
                    return "{$keyword} Jobs | {$siteName}";
            }
        }

        // Localized Title with City Location
        switch ($locale) {
            case 'hi':
                return "{$cityName} में {$keyword} की नौकरियां | {$siteName}";
            case 'fr':
                return "Emplois {$keyword} à {$cityName} | {$siteName}";
            case 'de':
                return "{$keyword} Jobs in {$cityName} | {$siteName}";
            case 'es':
                return "Empleos de {$keyword} en {$cityName} | {$siteName}";
            case 'ar':
                return "وظائف {$keyword} في {$cityName} | {$siteName}";
            case 'mr':
                return "{$cityName} मध्ये {$keyword} नोकऱ्या | {$siteName}";
            case 'bn':
                return "{$cityName} এ {$keyword} চাকরি | {$siteName}";
            case 'ta':
                return "{$cityName} இல் {$keyword} வேலைகள் | {$siteName}";
            case 'te':
                return "{$cityName} లో {$keyword} ఉద్యోగాలు | {$siteName}";
            case 'gu':
                return "{$cityName} માં {$keyword} નોકરીઓ | {$siteName}";
            case 'pt':
                return "Vagas de {$keyword} em {$cityName} | {$siteName}";
            case 'ru':
                return "Вакансии {$keyword} в {$cityName} | {$siteName}";
            default:
                return "{$keyword} Jobs in {$cityName} | {$siteName}";
        }
    }

    /**
     * Generate localized Meta Description
     *
     * @param string $keyword
     * @param string|null $cityName
     * @param string $locale
     * @param string|null $siteName
     * @return string
     */
    public function getLocalizedMetaDescription(string $keyword, ?string $cityName = null, string $locale = 'en', ?string $siteName = null): string
    {
        $siteName = $siteName ?: config('app.name', 'Job Portal');
        $cityName = $cityName ? trim($cityName) : 'top locations';

        switch ($locale) {
            case 'hi':
                return "{$cityName} में नवीनतम {$keyword} नौकरियां खोजें और तुरंत ऑनलाइन आवेदन करें। {$siteName} पर सत्यापित नियोक्ताओं से जुड़ें।";
            case 'fr':
                return "Trouvez les dernières offres d'emploi {$keyword} à {$cityName}. Postulez directement en ligne sur {$siteName}.";
            case 'de':
                return "Entdecken Sie die neuesten {$keyword} Stellenangebote in {$cityName}. Jetzt einfach online bewerben auf {$siteName}.";
            case 'es':
                return "Encuentra las mejores ofertas de empleo de {$keyword} en {$cityName}. Postula en línea hoy en {$siteName}.";
            case 'ar':
                return "ابحث عن أحدث وظائف {$keyword} في {$cityName} وقدم طلبك مباشرة عبر الإنترنت على {$siteName}.";
            case 'mr':
                return "{$cityName} मधील नवीनतम {$keyword} नोकऱ्या शोधा आणि थेट ऑनलाइन अर्ज करा. {$siteName} वर नोंदणी करा.";
            case 'bn':
                return "{$cityName} এ সর্বশেষ {$keyword} চাকরি খুঁজুন এবং সরাসরি অনলাইনে আবেদন করুন। {$siteName} এর সাথে থাকুন।";
            default:
                return "Find and apply to the latest {$keyword} jobs in {$cityName}. Explore verified employers, competitive salaries, and apply online on {$siteName}.";
        }
    }

    /**
     * Generate dynamic hreflang alternate URL tags
     *
     * @param string $currentUrl
     * @param Collection|array $languages
     * @return array
     */
    public function generateHreflangTags(string $currentUrl, $languages): array
    {
        $tags = [];
        $parsedUrl = parse_url($currentUrl);
        $baseUrl = ($parsedUrl['scheme'] ?? 'http') . '://' . ($parsedUrl['host'] ?? '') . (isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '') . ($parsedUrl['path'] ?? '');
        
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        foreach ($languages as $lang) {
            $iso = is_object($lang) ? $lang->iso_code : ($lang['iso_code'] ?? 'en');
            $params = $queryParams;
            $params['lang'] = $iso;
            $langUrl = $baseUrl . '?' . http_build_query($params);

            $tags[] = [
                'hreflang' => $iso,
                'href' => $langUrl
            ];
        }

        // x-default points to default or clean base URL
        $tags[] = [
            'hreflang' => 'x-default',
            'href' => $baseUrl
        ];

        return $tags;
    }
}
