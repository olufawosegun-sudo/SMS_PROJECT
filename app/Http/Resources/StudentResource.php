<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'admission_no' => $this->admission_no,
            'status' => $this->status,
            'admission_date' => $this->admission_date?->format('Y-m-d'),

            // User information (sanitized)
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'full_name' => $this->user->first_name.' '.$this->user->last_name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'gender' => $this->user->gender,
            'date_of_birth' => $this->user->date_of_birth,
            'age' => $this->user->date_of_birth ?
                Carbon::parse($this->user->date_of_birth)->age : null,
            'profile_photo_url' => $this->user->profile_photo ?
                asset('storage/'.$this->user->profile_photo) : null,

            // Class information
            'class' => [
                'id' => $this->schoolClass->id,
                'name' => $this->schoolClass->name,
            ],

            // Arm information (if exists)
            'arm' => $this->when($this->arm, [
                'id' => $this->arm?->id,
                'name' => $this->arm?->name,
            ]),

            // Branch information (if exists)
            'branch' => $this->when($this->schoolBranch, [
                'id' => $this->schoolBranch?->id,
                'name' => $this->schoolBranch?->name,
            ]),

            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
