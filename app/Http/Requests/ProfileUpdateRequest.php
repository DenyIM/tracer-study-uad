<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20'],
            'study_program' => ['required', 'string', 'max:100'],
            'graduation_year' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'phone' => ['required', 'string', 'max:20'],
            'npwp' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'fullname.required' => 'Nama lengkap harus diisi.',
            'nim.required' => 'NIM harus diisi.',
            'study_program.required' => 'Jurusan/prodi harus diisi.',
            'graduation_year.required' => 'Tahun lulus harus diisi.',
            'graduation_year.integer' => 'Tahun lulus harus berupa angka.',
            'graduation_year.min' => 'Tahun lulus minimal 2000.',
            'graduation_year.max' => 'Tahun lulus maksimal ' . (date('Y') + 5),
            'phone.required' => 'Nomor HP harus diisi.',
        ];
    }
}