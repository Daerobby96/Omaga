<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('dosen')?->id;
        return [
            'nidn'               => 'required|string|max:20|unique:dosen,nidn'.($id ? ",".$id : ''),
            'nama_lengkap'       => 'required|string|max:100',
            'email'              => 'required|email|unique:users,email'.($id ? ','.$this->route('dosen')->user_id : ''),
            'password'           => $id ? 'nullable|min:8' : 'required|min:8',
            'program_studi'      => 'required|string|max:100',
            'fakultas'           => 'required|string|max:100',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'no_hp'              => 'nullable|string|max:15',
            'kuota_bimbingan'    => 'required|integer|min:1|max:20',
        ];
    }
}
