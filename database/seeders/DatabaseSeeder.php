<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraTenant\Models\Tenant;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()->first();

        if ( ! $tenant) {
            $this->command?->error('Tenants not found. Please run TenantSeeder first.');
            return;
        }

        $tenant->makeCurrent();

        $this->seedCustomPages($tenant);
    }

    private function seedCustomPages(Tenant $tenant): void
    {
        $locales = config('app.supported_locales', ['en', 'fa']);

        $categories = [
            [
                'base_name' => [
                    'en' => 'General',
                    'fa' => 'عمومی',
                ],
                'base_description' => [
                    'en' => 'General description goes here',
                    'fa' => 'توضیحات عمومی اینجا قرار می‌گیرد',
                ],
                'status'       => true,
                'custom_pages' => [
                    [
                        'base_name' => [
                            'en' => 'About Us',
                            'fa' => 'درباره ما',
                        ],
                        'base_description' => [
                            'en' => 'About us page content',
                            'fa' => 'محتوای صفحه درباره ما',
                        ],
                        'status' => true,
                    ],
                    [
                        'base_name' => [
                            'en' => 'Contact',
                            'fa' => 'تماس با ما',
                        ],
                        'base_description' => [
                            'en' => 'Contact page content',
                            'fa' => 'محتوای صفحه تماس',
                        ],
                        'status' => true,
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $categoryName = $this->buildTranslations($categoryData['base_name'], $locales);
            $categoryDescription = $this->buildTranslations($categoryData['base_description'], $locales);

            $category = CustomPageCategory::query()->updateOrCreate(
                ['slug' => Str::slug($categoryName['en'])],
                [
                    'name'        => $categoryName,
                    'description' => $categoryDescription,
                    'status'      => $categoryData['status'],
                ],
            );

            foreach ($categoryData['custom_pages'] as $pageData) {
                $pageName = $this->buildTranslations($pageData['base_name'], $locales);
                $pageDescription = $this->buildTranslations($pageData['base_description'], $locales);

                $category->customPages()->updateOrCreate(
                    ['slug' => Str::slug($pageName['en'])],
                    [
                        'name'        => $pageName,
                        'description' => $pageDescription,
                        'status'      => $pageData['status'],
                    ],
                );
            }
        }

        $this->command?->info("Custom pages seeded successfully for {$tenant->slug} tenant.");
    }

    /**
     * @param  array<string, string>  $baseTranslations
     * @param  array<int, string>  $locales
     * @return array<string, string>
     */
    private function buildTranslations(array $baseTranslations, array $locales, string $fallback = 'en'): array
    {
        $translations = [];

        foreach ($locales as $locale) {
            $translations[$locale] = $baseTranslations[$locale] ?? $baseTranslations[$fallback] ?? '';
        }

        return $translations;
    }
}
