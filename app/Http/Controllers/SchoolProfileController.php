<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SchoolProfileController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;

        // Ensure slug exists
        if (empty($school->slug)) {
            $baseSlug = Str::slug($school->name) ?: 'school-'.$school->id;
            $slug = $baseSlug;
            $counter = 1;
            while (School::where('slug', $slug)->where('id', '!=', $school->id)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
            $school->update(['slug' => $slug, 'subdomain' => $slug]);
            $school->refresh();
        }

        $countries = School::getCountryCurrencyMap();

        return view('school-profile.index', compact('school', 'countries'));
    }

    public function update(Request $request)
    {
        $school = Auth::user()->school;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', Rule::unique('schools', 'slug')->ignore($school->id)],
            'motto' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'website' => ['nullable', 'url', 'max:200'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'max:10'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            // Website customization
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'portal_theme' => ['nullable', 'string', 'max:50'],
            'welcome_title' => ['nullable', 'string', 'max:255'],
            'welcome_message' => ['nullable', 'string'],
            'about_text' => ['nullable', 'string'],
            'portal_hero_title' => ['nullable', 'string', 'max:255'],
            'portal_hero_subtitle' => ['nullable', 'string', 'max:255'],
            'admission_status' => ['required', 'in:open,closed'],
            // Official Banking & Payment Information
            'bank_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'account_name' => ['nullable', 'string', 'max:200'],
            'bank_branch' => ['nullable', 'string', 'max:150'],
            'bank_sort_code' => ['nullable', 'string', 'max:50'],
            'momo_number' => ['nullable', 'string', 'max:50'],
            'momo_network' => ['nullable', 'string', 'max:50'],
            'payment_instructions' => ['nullable', 'string', 'max:1000'],
            // Educational system settings
            'educational_system' => ['nullable', 'string', 'max:50'],
            'term_system' => ['nullable', 'string', 'in:3_terms,2_semesters'],
            'pass_mark' => ['nullable', 'integer', 'min:1', 'max:100'],
            // Social Media
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            // Images
            'school_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg,webp', 'max:2048'],
            'hero_banner_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        // Process slug
        if (! empty($validated['slug'])) {
            $slug = Str::slug($validated['slug']);
            $validated['slug'] = $slug;
            $validated['subdomain'] = $slug;
        }

        // Auto-assign currency based on Country if currency fields were not explicitly overridden
        if (! empty($validated['country'])) {
            $currDefaults = School::getCurrencyForCountry($validated['country']);
            if (empty($validated['currency'])) {
                $validated['currency'] = $currDefaults['code'];
            }
            if (empty($validated['currency_symbol'])) {
                $validated['currency_symbol'] = $currDefaults['symbol'];
            }
        }

        // Logo upload
        if ($request->hasFile('school_logo')) {
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }
            $validated['logo'] = $request->file('school_logo')->store('school-logos', 'public');
        }

        // Hero banner image upload
        if ($request->hasFile('hero_banner_image')) {
            if ($school->hero_banner && Storage::disk('public')->exists($school->hero_banner)) {
                Storage::disk('public')->delete($school->hero_banner);
            }
            $validated['hero_banner'] = $request->file('hero_banner_image')->store('school-banners', 'public');
        }

        $school->update($validated);

        return redirect()->back()->with('success', 'School profile, country & currency settings updated successfully!');
    }
}
