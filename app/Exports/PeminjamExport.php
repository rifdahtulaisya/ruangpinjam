<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $exportWithPassword;

    public function __construct($exportWithPassword = true)
    {
        $this->exportWithPassword = $exportWithPassword;
    }

    public function collection()
    {
        return User::where('role', 'peminjam')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Nama Lengkap',
            'Username',
            'Email',
        ];

        if ($this->exportWithPassword) {
            $headings[] = 'Password';
        }

        return $headings;
    }

    public function map($user): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $data = [
            $rowNumber,
            $user->name,
            $user->username,
            $user->email ?? '-',
        ];

        if ($this->exportWithPassword) {

            $plainPassword = $user->plain_password ?? 'Tidak tersedia';

            $data[] = $plainPassword;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }
}