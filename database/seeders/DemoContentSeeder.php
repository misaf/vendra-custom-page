<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Database\Seeders;

use Illuminate\Support\Facades\Validator;
use Misaf\VendraCustomPage\Database\Factories\CustomPageCategoryFactory;
use Misaf\VendraCustomPage\Database\Factories\CustomPageFactory;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraSupport\Database\Seeders\TenantDemoContentSeeder;
use Misaf\VendraTenant\Models\Tenant;

final class DemoContentSeeder extends TenantDemoContentSeeder
{
    protected function seedFactoryRecords(Tenant $tenant): void
    {
        CustomPageCategoryFactory::new()
            ->forTenant($tenant)
            ->enabled()
            ->count(2)
            ->create()
            ->each(fn(CustomPageCategory $category): mixed => CustomPageFactory::new()
                ->forCategory($category)
                ->enabled()
                ->count(2)
                ->create());
    }

    protected function seedFixtureRecord(Tenant $tenant, array $record): void
    {
        $data = $this->validatedFixtureRecord($record);

        $this->handleSeedFixtureRecord($tenant, $data);
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     custom_pages: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: bool
     *     }>
     * } $data
     */
    private function handleSeedFixtureRecord(Tenant $tenant, array $data): void
    {
        $category = new CustomPageCategory([
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => $data['slug'],
            'status'      => $data['status'],
        ]);

        $category->tenant_id = $tenant->id;
        $category->save();

        foreach ($data['custom_pages'] as $pageRecord) {
            $this->handleCustomPageFixtureRecord($tenant, $category, $pageRecord);
        }
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool
     * } $pageRecord
     */
    private function handleCustomPageFixtureRecord(Tenant $tenant, CustomPageCategory $category, array $pageRecord): void
    {
        $page = $category->customPages()->make([
            'name'        => $pageRecord['name'],
            'description' => $pageRecord['description'],
            'slug'        => $pageRecord['slug'],
            'status'      => $pageRecord['status'],
        ]);

        $page->tenant_id = $tenant->id;
        $page->save();
    }

    /**
     * @param array<string, mixed> $record
     *
     * @return array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool,
     *     custom_pages: list<array{
     *         name: non-empty-array<string, string>,
     *         description: non-empty-array<string, string>,
     *         slug: non-empty-array<string, string>,
     *         status: bool
     *     }>
     * }
     */
    private function validatedFixtureRecord(array $record): array
    {
        /** @var array{
         *     name: non-empty-array<string, string>,
         *     description: non-empty-array<string, string>,
         *     slug: non-empty-array<string, string>,
         *     status: bool,
         *     custom_pages: list<array{
         *         name: non-empty-array<string, string>,
         *         description: non-empty-array<string, string>,
         *         slug: non-empty-array<string, string>,
         *         status: bool
         *     }>
         * } $validated
         */
        $validated = Validator::make(
            data: $record,
            rules: [
                'name'                            => ['required', 'array', 'min:1'],
                'name.*'                          => ['required', 'string'],
                'description'                     => ['required', 'array', 'min:1'],
                'description.*'                   => ['required', 'string'],
                'slug'                            => ['required', 'array', 'min:1'],
                'slug.*'                          => ['required', 'string'],
                'status'                          => ['required', 'boolean'],
                'custom_pages'                    => ['required', 'array', 'list'],
                'custom_pages.*'                  => ['required', 'array:name,description,slug,status'],
                'custom_pages.*.name'             => ['required', 'array', 'min:1'],
                'custom_pages.*.name.*'           => ['required', 'string'],
                'custom_pages.*.description'      => ['required', 'array', 'min:1'],
                'custom_pages.*.description.*'    => ['required', 'string'],
                'custom_pages.*.slug'             => ['required', 'array', 'min:1'],
                'custom_pages.*.slug.*'           => ['required', 'string'],
                'custom_pages.*.status'           => ['required', 'boolean'],
            ],
        )->validate();

        return $validated;
    }
}
