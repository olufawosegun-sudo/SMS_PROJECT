<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryCurrencyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setupOwnerUser(array $schoolAttributes = []): array
    {
        $school = School::factory()->create($schoolAttributes);
        $role = Role::factory()->create(['name' => 'Owner']);
        $user = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $role->id,
        ]);

        return [$school, $user];
    }

    public function test_school_resolves_currency_dynamically_from_country(): void
    {
        // Nigeria
        $nigeriaSchool = School::factory()->create(['country' => 'Nigeria']);
        $this->assertEquals('₦', $nigeriaSchool->currency_symbol);
        $this->assertEquals('NGN', $nigeriaSchool->currency_code);

        // Ghana
        $ghanaSchool = School::factory()->create(['country' => 'Ghana']);
        $this->assertEquals('GH₵', $ghanaSchool->currency_symbol);
        $this->assertEquals('GHS', $ghanaSchool->currency_code);

        // Sierra Leone
        $slSchool = School::factory()->create(['country' => 'Sierra Leone']);
        $this->assertEquals('Le', $slSchool->currency_symbol);
        $this->assertEquals('SLE', $slSchool->currency_code);

        // The Gambia
        $gambiaSchool = School::factory()->create(['country' => 'The Gambia']);
        $this->assertEquals('D', $gambiaSchool->currency_symbol);
        $this->assertEquals('GMD', $gambiaSchool->currency_code);

        // Liberia
        $liberiaSchool = School::factory()->create(['country' => 'Liberia']);
        $this->assertEquals('L$', $liberiaSchool->currency_symbol);
        $this->assertEquals('LRD', $liberiaSchool->currency_code);

        // Kenya
        $kenyaSchool = School::factory()->create(['country' => 'Kenya']);
        $this->assertEquals('KSh', $kenyaSchool->currency_symbol);
        $this->assertEquals('KES', $kenyaSchool->currency_code);

        // United Kingdom
        $ukSchool = School::factory()->create(['country' => 'United Kingdom']);
        $this->assertEquals('£', $ukSchool->currency_symbol);
        $this->assertEquals('GBP', $ukSchool->currency_code);

        // United States
        $usSchool = School::factory()->create(['country' => 'United States']);
        $this->assertEquals('$', $usSchool->currency_symbol);
        $this->assertEquals('USD', $usSchool->currency_code);
    }

    public function test_owner_can_view_system_settings_with_currency_information(): void
    {
        [$school, $owner] = $this->setupOwnerUser(['country' => 'Ghana']);

        $response = $this->actingAs($owner)->get(route('system-settings.index'));

        $response->assertStatus(200);
        $response->assertSee('System Configuration');
        $response->assertSee('Country & Currency', false);
        $response->assertSee('Ghana (GHS • GH₵)', false);
        $response->assertSee('GH₵', false);
    }

    public function test_owner_can_update_country_and_currency_in_system_settings(): void
    {
        [$school, $owner] = $this->setupOwnerUser(['country' => 'Nigeria']);

        $response = $this->actingAs($owner)->post(route('system-settings.update'), [
            'country' => 'Ghana',
            'currency' => 'GHS',
            'currency_symbol' => 'GH₵',
            'backup_freq' => 'daily',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $school->refresh();
        $this->assertEquals('Ghana', $school->country);
        $this->assertEquals('GHS', $school->currency);
        $this->assertEquals('GH₵', $school->currency_symbol);
    }

    public function test_updating_country_in_school_profile_auto_derives_currency(): void
    {
        [$school, $owner] = $this->setupOwnerUser(['country' => 'Nigeria']);

        $response = $this->actingAs($owner)->post(route('school-profile.update'), [
            'name' => $school->name,
            'country' => 'Sierra Leone',
            'state' => 'Western Area',
            'city' => 'Freetown',
            'admission_status' => 'open',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $school->refresh();
        $this->assertEquals('Sierra Leone', $school->country);
        $this->assertEquals('SLE', $school->currency);
        $this->assertEquals('Le', $school->currency_symbol);
    }
}
