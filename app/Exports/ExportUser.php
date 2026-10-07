<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUser implements FromCollection, WithHeadings
{
    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }
    public function headings(): array
    {
        return [
            'Nom',
            'Email',
            'Téléphone',
            'Adresse',
            'Ville',
            'points'
        ];
    }
}
