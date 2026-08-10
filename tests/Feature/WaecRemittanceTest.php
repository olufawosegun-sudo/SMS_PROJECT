<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Models\WaecCandidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaecRemittanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_and_principal_can_access_waec_remittance_page()
    {
        $school = School::factory()->create();
        $ownerRole = Role::factory()->create(['name' => 'Owner']);

        $owner = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $ownerRole->id,
        ]);

        $response = $this->actingAs($owner)->get(route('owner.waec.remittance.index'));
        $response->assertStatus(200);
        $response->assertSee('WAEC Payment & Remittance', false);

        $principalRole = Role::factory()->create(['name' => 'Principal']);
        $principal = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $principalRole->id,
        ]);

        $responsePrincipal = $this->actingAs($principal)->get(route('principal.waec.remittance.index'));
        $responsePrincipal->assertStatus(200);
        $responsePrincipal->assertSee('WAEC Payment & Remittance', false);
    }

    public function test_owner_can_record_waec_remittance()
    {
        $school = School::factory()->create();
        $ownerRole = Role::factory()->create(['name' => 'Owner']);
        $owner = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $ownerRole->id,
        ]);

        $session = AcademicSession::factory()->create(['school_id' => $school->id]);
        $class = SchoolClass::factory()->create(['school_id' => $school->id]);
        $studentUser = User::factory()->create(['school_id' => $school->id]);
        $student = Student::factory()->create([
            'school_id' => $school->id,
            'user_id' => $studentUser->id,
            'class_id' => $class->id,
        ]);

        $candidate = WaecCandidate::create([
            'school_id' => $school->id,
            'student_id' => $student->id,
            'session_id' => $session->id,
            'class_id' => $class->id,
            'total_fee' => 30000,
            'amount_paid' => 30000,
            'payment_status' => 'paid',
            'status' => 'registered',
            'registration_date' => now()->toDateString(),
            'registered_by' => $owner->id,
        ]);

        $response = $this->actingAs($owner)->post(route('owner.waec.remittance.store'), [
            'session_id' => $session->id,
            'candidate_ids' => [$candidate->id],
            'total_amount' => 30000,
            'payment_method' => 'bank_transfer',
            'waec_transaction_reference' => 'WAEC-TEL-100293',
            'payment_date' => date('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('waec_remittances', [
            'school_id' => $school->id,
            'waec_transaction_reference' => 'WAEC-TEL-100293',
            'total_candidates_count' => 1,
        ]);

        $candidate->refresh();
        $this->assertEquals('exam_ready', $candidate->status);
        $this->assertNotNull($candidate->waec_remittance_id);
    }

    public function test_remittance_submission_fails_without_required_fields()
    {
        $school = School::factory()->create();
        $ownerRole = Role::factory()->create(['name' => 'Owner']);
        $owner = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $ownerRole->id,
        ]);

        $response = $this->actingAs($owner)->post(route('owner.waec.remittance.store'), [
            // Missing session_id, candidate_ids, waec_transaction_reference
        ]);

        $response->assertSessionHasErrors(['session_id', 'candidate_ids', 'waec_transaction_reference']);
    }

    public function test_principal_can_record_waec_remittance()
    {
        $school = School::factory()->create();
        $principalRole = Role::factory()->create(['name' => 'Principal']);
        $principal = User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $principalRole->id,
        ]);

        $session = AcademicSession::factory()->create(['school_id' => $school->id]);
        $class = SchoolClass::factory()->create(['school_id' => $school->id]);
        $studentUser = User::factory()->create(['school_id' => $school->id]);
        $student = Student::factory()->create([
            'school_id' => $school->id,
            'user_id' => $studentUser->id,
            'class_id' => $class->id,
        ]);

        $candidate = WaecCandidate::create([
            'school_id' => $school->id,
            'student_id' => $student->id,
            'session_id' => $session->id,
            'class_id' => $class->id,
            'total_fee' => 30000,
            'amount_paid' => 30000,
            'payment_status' => 'paid',
            'status' => 'registered',
            'registration_date' => now()->toDateString(),
            'registered_by' => $principal->id,
        ]);

        $response = $this->actingAs($principal)->post(route('principal.waec.remittance.store'), [
            'session_id' => $session->id,
            'candidate_ids' => [$candidate->id],
            'total_amount' => 30000,
            'payment_method' => 'bank_transfer',
            'waec_transaction_reference' => 'WAEC-TEL-PRIN-991',
            'payment_date' => date('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('waec_remittances', [
            'school_id' => $school->id,
            'waec_transaction_reference' => 'WAEC-TEL-PRIN-991',
            'total_candidates_count' => 1,
        ]);
    }
}
