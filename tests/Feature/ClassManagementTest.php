<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setupOwnerUser(): array
    {
        $school = School::factory()->create();
        $role = Role::factory()->create(['name' => 'Owner']);
        $user = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $role->id,
        ]);

        return [$school, $user];
    }

    public function test_owner_can_view_classes_index_page()
    {
        [$school, $owner] = $this->setupOwnerUser();

        $response = $this->actingAs($owner)->get(route('classes.index'));
        $response->assertStatus(200);
        $response->assertSee('Classes Management', false);
        $response->assertSee('Create Class', false);
    }

    public function test_owner_can_create_class_with_any_custom_level()
    {
        [$school, $owner] = $this->setupOwnerUser();

        // Testing dynamic non-enum level (e.g. SSS 1 Science, Basic 7, Grade 10, etc.)
        $response = $this->actingAs($owner)->post(route('classes.store'), [
            'name' => 'SSS 1 Science Diamond',
            'level' => 'SSS1-Science',
            'description' => 'Science department class',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('classes', [
            'school_id' => $school->id,
            'name' => 'SSS 1 Science Diamond',
            'level' => 'SSS1-Science',
        ]);
    }

    public function test_owner_can_update_class()
    {
        [$school, $owner] = $this->setupOwnerUser();

        $class = SchoolClass::create([
            'school_id' => $school->id,
            'name' => 'JSS 1',
            'level' => 'JSS1',
        ]);

        $response = $this->actingAs($owner)->put(route('classes.update', $class->id), [
            'name' => 'JSS 1 (Basic 7)',
            'level' => 'JHS 1',
            'description' => 'Junior Secondary First Year',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('classes', [
            'id' => $class->id,
            'name' => 'JSS 1 (Basic 7)',
            'level' => 'JHS 1',
        ]);
    }

    public function test_owner_can_add_arm_to_class()
    {
        [$school, $owner] = $this->setupOwnerUser();

        $class = SchoolClass::create([
            'school_id' => $school->id,
            'name' => 'SS 2 Science',
            'level' => 'SS2',
        ]);

        $response = $this->actingAs($owner)->post(route('class-arms.store'), [
            'class_id' => $class->id,
            'name' => 'Diamond',
            'capacity' => 40,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('class_arms', [
            'class_id' => $class->id,
            'name' => 'Diamond',
            'capacity' => 40,
        ]);
    }

    public function test_owner_can_delete_empty_class()
    {
        [$school, $owner] = $this->setupOwnerUser();

        $class = SchoolClass::create([
            'school_id' => $school->id,
            'name' => 'Grade 12',
            'level' => 'Grade 12',
        ]);

        $response = $this->actingAs($owner)->delete(route('classes.destroy', $class->id));

        $response->assertRedirect();
        $this->assertSoftDeleted('classes', [
            'id' => $class->id,
        ]);
    }
}
