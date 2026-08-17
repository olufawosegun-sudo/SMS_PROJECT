<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\FeeCategory;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvoiceAndFinancialReportTest extends TestCase
{
    use RefreshDatabase;

    protected $school;

    protected $ownerUser;

    protected $student;

    protected $session;

    protected $term;

    protected $feeCategory1;

    protected $feeCategory2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::factory()->create([
            'country' => 'Ghana',
            'currency' => 'GHS',
            'currency_symbol' => 'GH₵',
        ]);

        $ownerRole = Role::factory()->create([
            'school_id' => $this->school->id,
            'name' => 'Owner',
        ]);

        $this->ownerUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => $ownerRole->id,
        ]);

        $studentUser = User::factory()->create([
            'school_id' => $this->school->id,
            'first_name' => 'Ama',
            'last_name' => 'Kofi',
        ]);

        $this->student = Student::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $this->school->id,
            'user_id' => $studentUser->id,
            'admission_no' => 'AGS-2026-001',
            'status' => 'active',
        ]);

        $this->session = AcademicSession::create([
            'school_id' => $this->school->id,
            'name' => '2026/2027',
            'start_date' => '2026-09-01',
            'end_date' => '2027-07-31',
            'is_current' => true,
        ]);

        $this->term = AcademicTerm::create([
            'school_id' => $this->school->id,
            'session_id' => $this->session->id,
            'name' => 'First Term',
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-15',
            'is_current' => true,
        ]);

        $this->feeCategory1 = FeeCategory::create([
            'school_id' => $this->school->id,
            'name' => 'Tuition Fee',
            'amount' => 1500.00,
            'status' => 'active',
        ]);

        $this->feeCategory2 = FeeCategory::create([
            'school_id' => $this->school->id,
            'name' => 'Science Lab Fee',
            'amount' => 350.00,
            'status' => 'active',
        ]);
    }

    public function test_can_view_create_invoice_page()
    {
        $response = $this->actingAs($this->ownerUser)
            ->get(route('invoices.create'));

        $response->assertStatus(200);
        $response->assertSee('Create Invoice');
        $response->assertSee('Tuition Fee');
        $response->assertSee('Science Lab Fee');
        $response->assertSee('Ama Kofi');
    }

    public function test_can_create_invoice_with_multiple_fee_categories()
    {
        $response = $this->actingAs($this->ownerUser)
            ->post(route('invoices.store'), [
                'student_id' => $this->student->id,
                'due_date' => '2026-10-31',
                'items' => [
                    [
                        'fee_category_id' => $this->feeCategory1->id,
                        'amount' => 1500.00,
                    ],
                    [
                        'fee_category_id' => $this->feeCategory2->id,
                        'amount' => 350.00,
                    ],
                ],
            ]);

        $response->assertRedirect(route('invoices.index'));
        $this->assertDatabaseHas('invoices', [
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'total_amount' => 1850.00,
            'balance' => 1850.00,
            'status' => 'unpaid',
        ]);

        $this->assertDatabaseCount('invoice_items', 2);
    }

    public function test_can_create_invoice_by_typing_custom_category_name()
    {
        $response = $this->actingAs($this->ownerUser)
            ->post(route('invoices.store'), [
                'student_id' => $this->student->id,
                'due_date' => '2026-11-30',
                'items' => [
                    [
                        'category_name' => 'Graduation Robe & Hat',
                        'amount' => 450.00,
                    ],
                ],
            ]);

        $response->assertRedirect(route('invoices.index'));
        $this->assertDatabaseHas('fee_categories', [
            'school_id' => $this->school->id,
            'name' => 'Graduation Robe & Hat',
        ]);
        $this->assertDatabaseHas('invoices', [
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'total_amount' => 450.00,
        ]);
    }

    public function test_financial_report_displays_profit_and_loss_summary()
    {
        $response = $this->actingAs($this->ownerUser)
            ->get(route('financial-reports.index'));

        $response->assertStatus(200);
        $response->assertSee('School Financial Statement');
        $response->assertSee('Net Profit');
        $response->assertSee('Profit Margin');
        $response->assertSee('Collection Rate');
    }

    public function test_can_set_academic_session_as_active()
    {
        $session2 = AcademicSession::create([
            'school_id' => $this->school->id,
            'name' => '2027/2028',
            'start_date' => '2027-09-01',
            'end_date' => '2028-07-31',
            'is_current' => false,
        ]);

        $response = $this->actingAs($this->ownerUser)
            ->post(route('sessions.set-active', $session2->id));

        $response->assertStatus(302);
        $this->assertTrue($session2->fresh()->is_current);
        $this->assertFalse($this->session->fresh()->is_current);
    }

    public function test_can_set_academic_session_as_active_via_ajax()
    {
        $session2 = AcademicSession::create([
            'school_id' => $this->school->id,
            'name' => '2027/2028',
            'start_date' => '2027-09-01',
            'end_date' => '2028-07-31',
            'is_current' => false,
        ]);

        $response = $this->actingAs($this->ownerUser)
            ->postJson(route('sessions.set-active', $session2->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'session' => '2027/2028',
        ]);
        $this->assertTrue($session2->fresh()->is_current);
    }

    public function test_payroll_disbursement_is_auto_recorded_as_expense()
    {
        $staffUser = User::factory()->create([
            'school_id' => $this->school->id,
            'first_name' => 'Kofi',
            'last_name' => 'Boateng',
        ]);

        $staff = Staff::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $this->school->id,
            'user_id' => $staffUser->id,
            'staff_no' => 'STF-2026-001',
            'staff_type' => 'Teacher',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->ownerUser)
            ->post(route('payroll.store'), [
                'staff_id' => $staff->id,
                'basic_salary' => 2000.00,
                'allowance' => 300.00,
                'deduction' => 100.00,
                'month' => 'October',
                'year' => 2026,
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('expenses', [
            'school_id' => $this->school->id,
            'amount' => 2200.00,
        ]);
    }

    public function test_guardian_can_view_public_invoice_payment_page()
    {
        $invoice = Invoice::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'session_id' => $this->session->id,
            'term_id' => $this->term->id,
            'invoice_number' => 'INV-2026-9999',
            'total_amount' => 1200.00,
            'paid_amount' => 0,
            'balance' => 1200.00,
            'status' => 'unpaid',
            'due_date' => now()->addDays(15),
        ]);

        $response = $this->get(route('invoices.public.pay', $invoice->uuid));

        $response->assertStatus(200);
        $response->assertSee('INV-2026-9999');
        $response->assertSee('Ama Kofi');
        $response->assertSee('1,200.00');
        $response->assertSee('Complete Payment');
    }

    public function test_guardian_can_make_payment_through_public_url()
    {
        $invoice = Invoice::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'session_id' => $this->session->id,
            'term_id' => $this->term->id,
            'invoice_number' => 'INV-2026-8888',
            'total_amount' => 800.00,
            'paid_amount' => 0,
            'balance' => 800.00,
            'status' => 'unpaid',
            'due_date' => now()->addDays(15),
        ]);

        $response = $this->post(route('invoices.public.process', $invoice->uuid), [
            'amount' => 800.00,
            'payment_method' => 'card',
            'payer_name' => 'John Kofi',
            'payer_phone' => '+233 20 123 4567',
        ]);

        $response->assertRedirect(route('invoices.public.receipt', $invoice->uuid));

        $invoice->refresh();
        $this->assertEquals(800.00, $invoice->paid_amount);
        $this->assertEquals(0.00, $invoice->balance);
        $this->assertEquals('paid', $invoice->status);

        $this->assertDatabaseHas('payments', [
            'school_id' => $this->school->id,
            'invoice_id' => $invoice->id,
            'amount' => 800.00,
            'status' => 'completed',
        ]);
    }

    public function test_guardian_can_view_printable_receipt()
    {
        $invoice = Invoice::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'session_id' => $this->session->id,
            'term_id' => $this->term->id,
            'invoice_number' => 'INV-2026-7777',
            'total_amount' => 500.00,
            'paid_amount' => 500.00,
            'balance' => 0,
            'status' => 'paid',
            'due_date' => now()->addDays(15),
        ]);

        $response = $this->get(route('invoices.public.receipt', $invoice->uuid));

        $response->assertStatus(200);
        $response->assertSee('INV-2026-7777');
        $response->assertSee('Payment Receipt');
        $response->assertSee('Print / Save PDF Receipt');
    }

    public function test_invoice_public_pay_displays_school_custom_and_country_bank_details()
    {
        $this->school->update([
            'country' => 'Ghana',
            'bank_name' => 'Ecobank Ghana',
            'account_number' => '004928192837',
            'account_name' => 'Springfield Academy Ghana Ltd',
            'momo_network' => 'MTN MoMo',
            'momo_number' => '0244998877',
        ]);

        $invoice = Invoice::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'session_id' => $this->session->id,
            'term_id' => $this->term->id,
            'invoice_number' => 'INV-GHA-1010',
            'total_amount' => 1500.00,
            'paid_amount' => 0,
            'balance' => 1500.00,
            'status' => 'unpaid',
            'due_date' => now()->addDays(15),
        ]);

        $response = $this->get(route('invoices.public.pay', $invoice->uuid));

        $response->assertStatus(200);
        $response->assertSee('Ecobank Ghana');
        $response->assertSee('004928192837');
        $response->assertSee('Springfield Academy Ghana Ltd');
        $response->assertSee('MTN MoMo');
        $response->assertSee('0244998877');
        $response->assertSee('Ghana');
    }
}
