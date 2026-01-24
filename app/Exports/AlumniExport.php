<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlumniExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $alumni;

    public function __construct($alumni)
    {
        $this->alumni = $alumni;
    }

    public function collection()
    {
        return $this->alumni;
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama Lengkap',
            'Email',
            'Program Studi',
            'Tahun Lulus',
            'No. Telepon',
            'NPWP',
            'Points',
            'Status Verifikasi',
            'Tanggal Bergabung',
            'Terakhir Login'
        ];
    }

    public function map($alumni): array
    {
        return [
            $alumni->nim,
            $alumni->fullname,
            $alumni->user->email,
            $alumni->study_program,
            $alumni->graduation_date ? $alumni->graduation_date->format('Y') : '-',
            $alumni->phone,
            $alumni->npwp ?? '-',
            $alumni->points ?? 0,
            $alumni->user->email_verified_at ? 'Terverifikasi' : 'Belum',
            $alumni->created_at->format('d-m-Y H:i:s'),
            $alumni->user->last_login_at ? $alumni->user->last_login_at->format('d-m-Y H:i') : 'Belum pernah'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFE0E0E0']
                ]
            ],
        ];
    }
}