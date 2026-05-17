<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Handle Facebook Link sanitization
        if ($this->has('facebook_link') && $this->facebook_link) {
            $link = $this->facebook_link;

            // Check if it's a domain-like string missing a protocol
            // e.g. facebook.com/user or www.facebook.com/user
            if (! preg_match('~^(?:f|ht)tps?://~i', $link)) {
                $link = 'https://'.ltrim($link, '/');
            }

            $this->merge([
                'facebook_link' => $link,
            ]);
        }

        // Sync name with full_name to avoid validation failure since 'name' is often hidden in UI
        if (! $this->name) {
            $this->merge(['name' => $this->full_name ?: $this->user()->name]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'bkash_number' => ['nullable', 'string', 'max:15', Rule::unique(User::class)->ignore($this->user()->id)],
            'nagad_number' => ['nullable', 'string', 'max:15', Rule::unique(User::class)->ignore($this->user()->id)],
            'facebook_link' => ['nullable', 'string', 'url', 'max:255'],
            'telegram_username' => ['nullable', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
