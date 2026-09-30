<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LotteryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name', '')),
            'active' => $this->boolean('active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('lotteries', 'slug')->ignore($this->route('lottery'))],
            'country' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'jackpot' => ['nullable', 'numeric', 'min:0'],
            'jackpot_currency' => ['nullable', 'string', 'size:3', 'alpha'],
            'jackpot_usd' => ['nullable', 'numeric', 'min:0'],
            'next_draw_at' => ['nullable', 'date'],
            'main_numbers_count' => ['nullable', 'integer', 'min:0', 'max:99'],
            'bonus_numbers_count' => ['nullable', 'integer', 'min:0', 'max:99'],
            'active' => ['boolean'],
        ];
    }

    public function validatedData(): array
    {
        $data = $this->validated();
        $data['jackpot_currency'] = isset($data['jackpot_currency']) ? strtoupper($data['jackpot_currency']) : null;

        return $data;
    }
}
