<?php

namespace App\Imports;

use App\Models\Peserta;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PesertaImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Peserta([
            'name'   => $row['name'],
            'email'  => $row['email'],
            'address'=> $row['address'],
            'no_hp'  => $row['no_hp'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name'  => ['required'],
            '*.email' => ['required', 'email'],
            '*.no_hp' => ['required', 'numeric'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required'  => 'Nama wajib diisi.',
            '*.email.required' => 'Email wajib diisi.',
            '*.email.email'    => 'Format email tidak valid.',
            '*.no_hp.required' => 'Nomor HP wajib diisi.',
            '*.no_hp.numeric'  => 'Nomor HP harus berupa angka.',
        ];
    }
}
