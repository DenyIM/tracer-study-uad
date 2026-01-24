<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Data Alumni - {{ date('d-m-Y') }}</title>
    <style>
        @page {
            size: landscape;
            margin: 15px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 7px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 5px 3px;
            text-align: left;
            font-weight: bold;
            font-size: 7px;
            white-space: nowrap;
        }

        td {
            border: 1px solid #dee2e6;
            padding: 4px 3px;
            font-size: 7px;
            vertical-align: middle;
        }

        .badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 6px;
            font-weight: bold;
            display: inline-block;
            min-width: 50px;
            text-align: center;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-primary {
            background-color: #cfe2ff;
            color: #084298;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 3px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .page-number:after {
            content: counter(page);
        }

        .no-wrap {
            white-space: nowrap;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Column widths */
        .col-no {
            width: 25px;
        }

        .col-nim {
            width: 80px;
        }

        .col-name {
            width: 100px;
        }

        .col-email {
            width: 120px;
        }

        .col-prodi {
            width: 70px;
        }

        .col-year {
            width: 50px;
        }

        .col-phone {
            width: 80px;
        }

        .col-npwp {
            width: 90px;
        }

        .col-points {
            width: 40px;
        }

        .col-status {
            width: 60px;
        }

        .col-date {
            width: 70px;
        }

        .col-login {
            width: 80px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA ALUMNI</h1>
        <p>Sistem Tracer Study - Universitas Ahmad Dahlan</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i:s') }} | Total Data: {{ count($alumni) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no text-center">No</th>
                <th class="col-nim">NIM</th>
                <th class="col-name">Nama Lengkap</th>
                <th class="col-email">Email</th>
                <th class="col-prodi">Program Studi</th>
                <th class="col-year">Tahun Lulus</th>
                <th class="col-phone">No. Telepon</th>
                <th class="col-npwp">NPWP</th>
                <th class="col-points text-center">Points</th>
                <th class="col-status">Status Email</th>
                <th class="col-date">Tanggal Bergabung</th>
                <th class="col-login">Terakhir Login</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alumni as $index => $item)
                <tr>
                    <td class="col-no text-center">{{ $index + 1 }}</td>
                    <td class="col-nim">{{ $item->nim }}</td>
                    <td class="col-name no-wrap">{{ $item->fullname }}</td>
                    <td class="col-email">{{ $item->user->email }}</td>
                    <td class="col-prodi">{{ $item->study_program }}</td>
                    <td class="col-year text-center">
                        {{ $item->graduation_date ? $item->graduation_date->format('Y') : '-' }}</td>
                    <td class="col-phone">{{ $item->phone ?? '-' }}</td>
                    <td class="col-npwp">{{ $item->npwp ?? '-' }}</td>
                    <td class="col-points text-center">
                        @if ($item->points && $item->points > 0)
                            <span class="badge badge-primary">{{ $item->points }}</span>
                        @else
                            <span class="text-center">-</span>
                        @endif
                    </td>
                    <td class="col-status">
                        @if ($item->user->email_verified_at)
                            <span class="badge badge-success">✓ Terverifikasi</span>
                        @else
                            <span class="badge badge-warning">✗ Belum</span>
                        @endif
                    </td>
                    <td class="col-date no-wrap">{{ $item->created_at->format('d-m-Y') }}</td>
                    <td class="col-login no-wrap">
                        @if ($item->user->last_login_at)
                            {{ $item->user->last_login_at->format('d-m-Y H:i') }}
                        @else
                            Belum pernah
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->fullname ?? 'System' }} | Halaman <span class="page-number"></span></p>
    </div>
</body>

</html>
