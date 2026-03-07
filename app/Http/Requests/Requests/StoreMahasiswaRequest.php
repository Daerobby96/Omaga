<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules(): array
    {
        $id = $this->route('mahasiswa')?->id;
        return [
            'nim'            => 'required|string|max:20|unique:mahasiswa,nim'.($id?",".$id:''),
            'nama_lengkap'   => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email'.($id?','.$this->route('mahasiswa')->user_id:''),
            'password'       => $id ? 'nullable|min:8' : 'required|min:8',
            'program_studi'  => 'required|string|max:100',
            'fakultas'       => 'required|string|max:100',
            'semester'       => 'required|integer|min:1|max:14',
            'angkatan'       => 'required|digits:4',
            'no_hp'          => 'nullable|string|max:15',
            'alamat'         => 'nullable|string|max:500',
            'ipk'            => 'nullable|numeric|min:0|max:4',
            'foto'           => 'nullable|image|max:2048',
            'cv'             => 'nullable|mimes:pdf|max:5120',
            'transkrip'      => 'nullable|mimes:pdf|max:5120',
        ];
    }
    public function messages(): array
    {
        return [
            'nim.required'   => 'NIM wajib diisi.',
            'nim.unique'     => 'NIM sudah terdaftar.',
            'email.unique'   => 'Email sudah digunakan.',
            'ipk.max'        => 'IPK maksimal 4.00.',
        ];
    }
}
