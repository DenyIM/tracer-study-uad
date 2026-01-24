<?php

namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AdminsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $admins;

    public function __construct($admins)
    {
        $this->admins = $admins;
    }

    public function collection()
    {
        return $this->admins;
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Email',
            'Jabatan',
            'No. Telepon',
            'Status Verifikasi',
            'Tanggal Bergabung',
            'Terakhir Login',
            'Status Aktif'
        ];
    }

    public function map($admin): array
    {
        return [
            $admin->fullname,
            $admin->user->email,
            $admin->job_title,
            $admin->phone ?? '-',
            $admin->user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi',
            $admin->created_at->format('d-m-Y'),
            $admin->user->last_login_at ? $admin->user->last_login_at->format('d-m-Y H:i') : 'Belum pernah',
            $admin->user->last_login_at ? 'Aktif' : 'Tidak Aktif'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
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