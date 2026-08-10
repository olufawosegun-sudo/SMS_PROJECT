<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubmitWaecPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && in_array(Auth::user()->role->name, ['Guardian', 'Student']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'candidate_id' => ['required', 'exists:waec_candidates,id'],
            'amount' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'payment_method' => ['required', 'string', 'in:bank_transfer,cash,cheque,pos,online'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'payment_notes' => ['nullable', 'string', 'max:1000'],
            'proof_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'candidate_id.required' => 'Please select a candidate.',
            'candidate_id.exists' => 'The selected candidate does not exist.',
            'amount.required' => 'Please enter the payment amount.',
            'amount.numeric' => 'Payment amount must be a valid number.',
            'amount.min' => 'Payment amount cannot be negative.',
            'amount.max' => 'Payment amount cannot exceed ₦999,999.99.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Invalid payment method selected.',
            'payment_date.required' => 'Please select the payment date.',
            'payment_date.date' => 'Invalid payment date format.',
            'payment_date.before_or_equal' => 'Payment date cannot be in the future.',
            'proof_document.file' => 'Please upload a valid file.',
            'proof_document.mimes' => 'Payment proof must be a PDF, JPG, JPEG, or PNG file.',
            'proof_document.max' => 'Payment proof file size must not exceed 2MB.',
        ];
    }
}
