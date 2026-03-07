<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nilai_pembimbingan' => 'required|numeric|min:0|max:100',
            'nilai_laporan'      => 'required|numeric|min:0|max:100',
            'nilai_seminar'      => 'required|numeric|min:0|max:100',
            'catatan_dosen'      => 'nullable|string|max:1000',
        ];
    }
}
