<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Database\Seeders;

use Illuminate\Support\Facades\Validator;
use Misaf\VendraCustomPage\Database\Factories\CustomPageCategoryFactory;
use Misaf\VendraCustomPage\Database\Factories\CustomPageFactory;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraSupport\Concerns\RequiresCurrentTenant;
use Misaf\VendraSupport\Database\Seeders\DemoContentSeeder as BaseDemoContentSeeder;
use Misaf\VendraTenant\Models\Tenant;

final class DemoContentSeeder extends BaseDemoContentSeeder
{
    use RequiresCurrentTenant;

    protected function seedFactories(): void
    {
        $tenant = $this->currentTenant();

        $this->seedFactoryRecords($tenant);
    }

    /**
     * @param list<array<string, mixed>> $records
     */
    protected function seedFixtures(array $records): void
    {
        $tenant = $this->currentTenant();

        foreach ($records as $record) {
            $this->seedFixtureRecord($tenant, $record);
        }
    }

    protected function seedFactoryRecords(Tenant $tenant): void
    {
        CustomPageCategoryFactory::new()
            ->forTenant($tenant)
            ->enabled()
            ->count(2)
            ->create()
            ->each(fn(CustomPageCategory $customPageCategory): mixed => CustomPageFactory::new()
                ->forCategory($customPageCategory)
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
        $customPageCategory = new CustomPageCategory([
            'name'        => $data['name'],
            'description' => $data['description'],
            'slug'        => $data['slug'],
            'status'      => $data['status'],
        ]);

        $customPageCategory->tenant_id = $tenant->id;
        $customPageCategory->save();

        foreach ($data['custom_pages'] as $customPageRecord) {
            $this->handleCustomPageFixtureRecord($tenant, $customPageCategory, $customPageRecord);
        }
    }

    /**
     * @param array{
     *     name: non-empty-array<string, string>,
     *     description: non-empty-array<string, string>,
     *     slug: non-empty-array<string, string>,
     *     status: bool
     * } $customPageRecord
     */
    private function handleCustomPageFixtureRecord(Tenant $tenant, CustomPageCategory $customPageCategory, array $customPageRecord): void
    {
        $customPage = $customPageCategory->customPages()->make([
            'name'        => $customPageRecord['name'],
            'description' => $customPageRecord['description'],
            'slug'        => $customPageRecord['slug'],
            'status'      => $customPageRecord['status'],
        ]);

        $customPage->tenant_id = $tenant->id;
        $customPage->save();
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
