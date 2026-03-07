<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal'       => 'required|date',
            'jam_masuk'     => 'required|',
            'jam_keluar'    => 'required|after:jam_masuk',
            'kegiatan'      => 'required|string|min:20|max:2000',
            'hasil'         => 'nullable|string|max:1000',
            'kendala'       => 'nullable|string|max:500',
            'foto_kegiatan' => 'nullable|image|max:2048',
        ];
    }
}
