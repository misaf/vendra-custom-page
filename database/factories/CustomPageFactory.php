<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraTenant\Models\Tenant;

/**
 * @extends Factory<CustomPage>
 */
final class CustomPageFactory extends Factory
{
    protected $model = CustomPage::class;

    public function definition(): array
    {
        return [
            'tenant_id'               => Tenant::factory(),
            'custom_page_category_id' => fn(array $attributes) => CustomPageCategory::factory()->forTenant($attributes['tenant_id']),
            'name'                    => ['en' => fake()->sentences(1, true)],
            'description'             => ['en' => fake()->realTextBetween(100, 200)],
            'slug'                    => ['en' => fn(array $attributes) => Str::slug($attributes['name']['en'])],
            'status'                  => fake()->boolean(80),
        ];
    }

    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn(): array => ['tenant_id' => $tenant->id]);
    }

    public function forCategory(CustomPageCategory $customPageCategory): static
    {
        return $this->state(fn(): array => [
            'tenant_id'               => $customPageCategory->tenant_id,
            'custom_page_category_id' => $customPageCategory->id,
        ]);
    }

    public function enabled(): static
    {
        return $this->state(fn(): array => ['status' => true]);
    }

    public function disabled(): static
    {
        return $this->state(fn(): array => ['status' => false]);
    }
}
