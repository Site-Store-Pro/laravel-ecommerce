<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\AdminSiteLabels;
use App\Models\Language;
use App\Models\SiteLabel;
use App\Models\SiteLabelSection;
use App\Models\SiteLabelTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSiteLabelsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("INSERT IGNORE INTO `user_roles` (`id`, `name`, `description`) VALUES 
            (1, 'User', 'Customer'),
            (2, 'Wholesale', 'Wholesale'),
            (3, 'Admin', 'Admin')");
    }

    public function test_search_matches_custom_override_and_default_fields(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_label_' . rand(1000, 9999) . '@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $section = SiteLabelSection::first();
        if (!$section) {
            $section = SiteLabelSection::create([
                'id'         => 1,
                'slug'       => 'general',
                'name'       => 'General',
                'sort_order' => 1,
            ]);
        }

        $customLabel = SiteLabel::create([
            'label_key'         => 'test.custom.unique_' . rand(1000, 9999),
            'section_id'        => $section->id,
            'file_name'         => 'custom-view.blade.php',
            'label_description' => 'A unique test label',
            'label_default'     => 'Standard Default Text',
            'label_custom'      => 'SuperCustomOverrideValue123',
        ]);

        $defaultLabel = SiteLabel::create([
            'label_key'         => 'test.default.unique_' . rand(1000, 9999),
            'section_id'        => $section->id,
            'file_name'         => 'default-view.blade.php',
            'label_description' => 'Another label description',
            'label_default'     => 'SearchableDefaultValue456',
            'label_custom'      => null,
        ]);

        // Search by custom override value
        Livewire::actingAs($admin)
            ->test(AdminSiteLabels::class)
            ->set('search', 'SuperCustomOverrideValue123')
            ->assertSee($customLabel->label_key)
            ->assertDontSee($defaultLabel->label_key);

        // Search by default value
        Livewire::actingAs($admin)
            ->test(AdminSiteLabels::class)
            ->set('search', 'SearchableDefaultValue456')
            ->assertSee($defaultLabel->label_key)
            ->assertDontSee($customLabel->label_key);
    }

    public function test_search_matches_translation_values(): void
    {
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin_trans_' . rand(1000, 9999) . '@support.local',
            'password'          => bcrypt('password'),
            'role_id'           => UserRole::Admin,
            'email_verified_at' => now(),
        ]);

        $lang = Language::firstOrCreate(
            ['code' => 'fr'],
            ['name' => 'French', 'native_name' => 'Français', 'is_default' => 0, 'is_active' => 1]
        );

        $section = SiteLabelSection::first();
        if (!$section) {
            $section = SiteLabelSection::create([
                'id'         => 1,
                'slug'       => 'general',
                'name'       => 'General',
                'sort_order' => 1,
            ]);
        }

        $label = SiteLabel::create([
            'label_key'         => 'test.trans.unique_' . rand(1000, 9999),
            'section_id'        => $section->id,
            'file_name'         => 'trans-view.blade.php',
            'label_description' => 'Translatable test label',
            'label_default'     => 'English Default Text',
            'label_custom'      => null,
        ]);

        SiteLabelTranslation::create([
            'site_label_id' => $label->id,
            'language_id'   => $lang->id,
            'label_value'   => 'TexteTraduitFrancais789',
        ]);

        Livewire::actingAs($admin)
            ->test(AdminSiteLabels::class)
            ->set('search', 'TexteTraduitFrancais789')
            ->assertSee($label->label_key);
    }
}
