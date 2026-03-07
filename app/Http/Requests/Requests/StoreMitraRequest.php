<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMitraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('mitra')?->id;
        return [
            'nama_perusahaan'  => 'required|string|max:200',
            'bidang_usaha'     => 'required|string|max:100',
            'nama_kontak'      => 'required|string|max:100',
            'jabatan_kontak'   => 'nullable|string|max:100',
            'email'            => 'required|email|unique:users,email'.($id ? ','.$this->route('mitra')->user_id : ''),
            'password'         => $id ? 'nullable|min:8' : 'required|min:8',
            'email_perusahaan' => 'required|email',
            'telepon'          => 'nullable|string|max:20',
            'alamat'           => 'required|string|max:500',
            'website'          => 'nullable|url|max:200',
            'deskripsi'        => 'nullable|string|max:1000',
            'kuota_magang'     => 'required|integer|min:0|max:100',
            'logo'             => 'nullable|image|max:2048',
        ];
    }
}
