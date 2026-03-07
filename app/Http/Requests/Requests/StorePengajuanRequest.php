<?php

namespace App\Http\Requests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mitra_id'            => 'required|exists:mitra,id',
            'tanggal_mulai'       => 'required|date|after_or_equal:today',
            'tanggal_selesai'     => 'required|date|after:tanggal_mulai',
            'bidang_kerja'        => 'required|string|max:200',
            'deskripsi_pekerjaan' => 'nullable|string|max:1000',
            'surat_pengantar'     => 'required|mimes:pdf|max:5120',
            'proposal'            => 'nullable|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'mitra_id.required'        => 'Perusahaan mitra wajib dipilih.',
            'mitra_id.exists'          => 'Perusahaan mitra tidak valid.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai minimal hari ini.',
            'tanggal_selesai.after'    => 'Tanggal selesai harus setelah tanggal mulai.',
            'surat_pengantar.required' => 'Surat pengantar wajib diunggah.',
            'surat_pengantar.mimes'    => 'Surat pengantar harus berformat PDF.',
        ];
    }
}
