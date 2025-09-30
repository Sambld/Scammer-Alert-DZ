<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScamCategory>
 */
class ScamCategoryFactory extends Factory
{
    /**
     * Predefined categories for consistency.
     */
    protected static array $categories = [
        [
            'name' => 'Financial Fraud',
            'name_ar' => 'الاحتيال المالي',
            'name_fr' => 'Fraude Financière',
            'description' => 'Scams involving fake investment schemes, cryptocurrency fraud, or false financial promises.',
            'description_ar' => 'عمليات احتيال تتضمن مخططات استثمار مزيفة أو احتيال العملات المشفرة أو وعود مالية كاذبة.',
            'description_fr' => 'Escroqueries impliquant de faux schémas d\'investissement, fraude de crypto-monnaie ou fausses promesses financières.',
        ],
        [
            'name' => 'Online Shopping Scam',
            'name_ar' => 'احتيال التسوق الإلكتروني',
            'name_fr' => 'Arnaque Achat en Ligne',
            'description' => 'Fake online stores, non-delivery of goods, or counterfeit products.',
            'description_ar' => 'متاجر إلكترونية مزيفة أو عدم تسليم البضائع أو منتجات مقلدة.',
            'description_fr' => 'Fausses boutiques en ligne, non-livraison de marchandises ou produits contrefaits.',
        ],
        [
            'name' => 'Romance Scam',
            'name_ar' => 'احتيال الرومانسية',
            'name_fr' => 'Arnaque Sentimentale',
            'description' => 'Fake dating profiles used to build relationships and extract money.',
            'description_ar' => 'ملفات تعارف مزيفة تستخدم لبناء علاقات واستخراج الأموال.',
            'description_fr' => 'Faux profils de rencontre utilisés pour établir des relations et extorquer de l\'argent.',
        ],
        [
            'name' => 'Job Offer Scam',
            'name_ar' => 'احتيال عروض العمل',
            'name_fr' => 'Arnaque Offre d\'Emploi',
            'description' => 'Fake job offers requiring upfront payments or personal information.',
            'description_ar' => 'عروض عمل مزيفة تتطلب دفعات مقدمة أو معلومات شخصية.',
            'description_fr' => 'Fausses offres d\'emploi nécessitant des paiements anticipés ou des informations personnelles.',
        ],
        [
            'name' => 'Phishing',
            'name_ar' => 'التصيد الاحتيالي',
            'name_fr' => 'Hameçonnage',
            'description' => 'Attempts to steal personal information through fake websites or messages.',
            'description_ar' => 'محاولات سرقة المعلومات الشخصية من خلال مواقع أو رسائل مزيفة.',
            'description_fr' => 'Tentatives de voler des informations personnelles via de faux sites ou messages.',
        ],
        [
            'name' => 'Social Media Scam',
            'name_ar' => 'احتيال وسائل التواصل الاجتماعي',
            'name_fr' => 'Arnaque Réseaux Sociaux',
            'description' => 'Scams conducted through social media platforms and messaging apps.',
            'description_ar' => 'عمليات احتيال تتم من خلال منصات وسائل التواصل الاجتماعي وتطبيقات المراسلة.',
            'description_fr' => 'Escroqueries menées via les plateformes de médias sociaux et applications de messagerie.',
        ],
        [
            'name' => 'Identity Theft',
            'name_ar' => 'سرقة الهوية',
            'name_fr' => 'Vol d\'Identité',
            'description' => 'Unauthorized use of personal information for fraudulent purposes.',
            'description_ar' => 'الاستخدام غير المصرح به للمعلومات الشخصية لأغراض احتيالية.',
            'description_fr' => 'Utilisation non autorisée d\'informations personnelles à des fins frauduleuses.',
        ],
        [
            'name' => 'Other',
            'name_ar' => 'أخرى',
            'name_fr' => 'Autre',
            'description' => 'Other types of scams not covered by the above categories.',
            'description_ar' => 'أنواع أخرى من عمليات الاحتيال غير مشمولة في الفئات المذكورة أعلاه.',
            'description_fr' => 'Autres types d\'escroqueries non couverts par les catégories ci-dessus.',
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = fake()->randomElement(static::$categories);
        
        return [
            'name' => $category['name'],
            'name_ar' => $category['name_ar'],
            'name_fr' => $category['name_fr'],
            'description' => $category['description'],
            'description_ar' => $category['description_ar'],
            'description_fr' => $category['description_fr'],
            'is_active' => fake()->boolean(90), // 90% active categories
        ];
    }

    /**
     * Create a specific category by name.
     */
    public function withName(string $name): static
    {
        $category = collect(static::$categories)->firstWhere('name', $name);
        
        if (!$category) {
            throw new \InvalidArgumentException("Category '{$name}' not found");
        }

        return $this->state(fn (array $attributes) => $category);
    }

    /**
     * Create an inactive category.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
