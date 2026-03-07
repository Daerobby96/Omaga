<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianMitraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nilai_kedisiplinan'     => 'required|numeric|min:0|max:100',
            'nilai_kemampuan_teknis' => 'required|numeric|min:0|max:100',
            'nilai_komunikasi'       => 'required|numeric|min:0|max:100',
            'nilai_inisiatif'        => 'required|numeric|min:0|max:100',
            'nilai_kerjasama'        => 'required|numeric|min:0|max:100',
            'catatan_mitra'          => 'nullable|string|max:1000',
        ];
    }
}
