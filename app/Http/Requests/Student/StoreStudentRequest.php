<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization will be handled by Policy
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $school = Auth::user()->school;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'class_id' => [
                'required',
                'integer',
                Rule::exists('classes', 'id')->where('school_id', $school->id)
            ],
            'arm_id' => [
                'nullable',
                'integer',
                Rule::exists('class_arms', 'id')->whereIn('class_id', function($query) use ($school) {
                    $query->select('id')->from('classes')->where('school_id', $school->id);
                })
            ],
            'admission_no' => [
                'nullable',
                'string',
                'max:50',
                'unique:students,admission_no',
                'regex:/^[A-Z0-9]+$/'
            ],
            'admission_date' => ['nullable', 'date', 'before_or_equal:today'],
            'school_branch_id' => [
                'nullable',
                'integer',
                Rule::exists('school_branches', 'id')->where(function ($query) use ($school) {
                    $query->where('school_id', $school->id)
                        ->where('status', 'active');
                })
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'date_of_birth' => 'date of birth',
            'profile_photo' => 'profile photo',
            'class_id' => 'class',
            'arm_id' => 'class arm',
            'admission_no' => 'admission number',
            'admission_date' => 'admission date',
            'school_branch_id' => 'school branch',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'The student\'s first name is required.',
            'last_name.required' => 'The student\'s last name is required.',
            'gender.required' => 'Please select the student\'s gender.',
            'gender.in' => 'The gender must be either male or female.',
            'date_of_birth.required' => 'The student\'s date of birth is required.',
            'date_of_birth.before' => 'The date of birth must be a past date.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'class_id.required' => 'Please select a class for the student.',
            'class_id.exists' => 'The selected class is invalid.',
            'arm_id.exists' => 'The selected class arm is invalid.',
            'admission_no.unique' => 'This admission number is already in use.',
            'admission_no.regex' => 'The admission number must contain only uppercase letters and numbers.',
            'profile_photo.image' => 'The file must be an image.',
            'profile_photo.max' => 'The profile photo must not exceed 2MB.',
            'school_branch_id.exists' => 'The selected school branch is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize admission number to uppercase if provided
        if ($this->has('admission_no') && $this->admission_no) {
            $this->merge([
                'admission_no' => strtoupper($this->admission_no),
            ]);
        }
    }
}
