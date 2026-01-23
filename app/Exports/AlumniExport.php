<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AlumniExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = User::where('role', 'alumni')
            ->with('alumni')
            ->orderBy('created_at', 'desc');

        if ($this->request->filled('study_program')) {
            $query->whereHas('alumni', function ($q) {
                $q->where('study_program', $this->request->study_program);
            });
        }

        if ($this->request->filled('graduation_year')) {
            $query->whereHas('alumni', function ($q) {
                $q->whereYear('graduation_date', $this->request->graduation_year);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama Lengkap',
            'Email',
            'Program Studi',
            'Tanggal Lahir',
            'Telepon',
            'Tanggal Lulus',
            'NPWP',
            'Email Verified',
            'Last Login',
            'Registered At'
        ];
    }

    public function map($user): array
    {
        return [
            $user->alumni->nim ?? '',
            $user->alumni->fullname ?? '',
            $user->email,
            $user->alumni->study_program ?? '',
            $user->alumni->date_of_birth ? Carbon::parse($user->alumni->date_of_birth)->format('d/m/Y') : '',
            $user->alumni->phone ?? '',
            $user->alumni->graduation_date ? Carbon::parse($user->alumni->graduation_date)->format('d/m/Y') : '',
            $user->alumni->npwp ?? '',
            $user->email_verified_at ? 'Yes' : 'No',
            $user->last_login_at ? Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Never',
            $user->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],

            'A' => ['width' => 15],
            'B' => ['width' => 30],
            'C' => ['width' => 30],
            'D' => ['width' => 20],
            'E' => ['width' => 15],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 20],
            'I' => ['width' => 15],
            'J' => ['width' => 20],
            'K' => ['width' => 20],
        ];
    }
}
