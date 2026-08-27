<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Language;
use Illuminate\Database\Seeder;

class MetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::query()->upsert([
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'is_default' => true, 'is_enabled' => true],
            ['code' => 'hi', 'name' => 'Hindi', 'native_name' => 'हिन्दी', 'is_default' => false, 'is_enabled' => true],
        ], ['code'], ['name', 'native_name', 'is_default', 'is_enabled']);

        Category::query()->upsert([
            ['category_group' => 'residential', 'slug' => 'house', 'name' => 'House', 'is_active' => true, 'sort_order' => 1],
            ['category_group' => 'residential', 'slug' => 'flat', 'name' => 'Flat', 'is_active' => true, 'sort_order' => 2],
            ['category_group' => 'residential', 'slug' => 'room', 'name' => 'Room', 'is_active' => true, 'sort_order' => 3],
            ['category_group' => 'residential', 'slug' => 'pg', 'name' => 'PG', 'is_active' => true, 'sort_order' => 4],
            ['category_group' => 'residential', 'slug' => 'hostel', 'name' => 'Hostel', 'is_active' => true, 'sort_order' => 5],
            ['category_group' => 'commercial', 'slug' => 'shop', 'name' => 'Shop', 'is_active' => true, 'sort_order' => 1],
            ['category_group' => 'commercial', 'slug' => 'office', 'name' => 'Office', 'is_active' => true, 'sort_order' => 2],
        ], ['slug'], ['category_group', 'name', 'is_active', 'sort_order']);

        City::query()->upsert([
            ['name' => 'Mumbai', 'state_name' => 'Maharashtra', 'country_name' => 'India', 'is_active' => true],
            ['name' => 'Delhi', 'state_name' => 'Delhi', 'country_name' => 'India', 'is_active' => true],
            ['name' => 'Bengaluru', 'state_name' => 'Karnataka', 'country_name' => 'India', 'is_active' => true],
        ], ['name', 'state_name', 'country_name'], ['is_active']);
    }
}
