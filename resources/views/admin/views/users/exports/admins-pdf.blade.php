<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Data Administrator - {{ date('d-m-Y') }}</title>
    <style>
        @page {
            size: landscape;
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
            white-space: nowrap;
        }

        td {
            border: 1px solid #dee2e6;
            padding: 5px 4px;
            font-size: 8px;
            vertical-align: middle;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
            min-width: 60px;
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

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
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

        /* Column widths */
        .col-no {
            width: 30px;
        }

        .col-name {
            width: 100px;
        }

        .col-email {
            width: 120px;
        }

        .col-job {
            width: 80px;
        }

        .col-phone {
            width: 80px;
        }

        .col-status {
            width: 70px;
        }

        .col-date {
            width: 70px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA ADMINISTRATOR</h1>
        <p>Sistem Tracer Study - Universitas Ahmad Dahlan</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i:s') }} | Total Data: {{ count($admins) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no text-center">No</th>
                <th class="col-name">Nama Lengkap</th>
                <th class="col-email">Email</th>
                <th class="col-job">Jabatan</th>
                <th class="col-phone">No. Telepon</th>
                <th class="col-status">Status Verifikasi</th>
                <th class="col-date">Tanggal Bergabung</th>
                <th>Terakhir Login</th>
                <th>Status Akun</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $index => $admin)
                <tr>
                    <td class="col-no text-center">{{ $index + 1 }}</td>
                    <td class="col-name no-wrap">{{ $admin->fullname }}</td>
                    <td class="col-email">{{ $admin->user->email }}</td>
                    <td class="col-job">{{ $admin->job_title }}</td>
                    <td class="col-phone">{{ $admin->phone ?? '-' }}</td>
                    <td class="col-status">
                        @if ($admin->user->email_verified_at)
                            <span class="badge badge-success">Terverifikasi</span>
                        @else
                            <span class="badge badge-warning">Belum</span>
                        @endif
                    </td>
                    <td class="col-date no-wrap">{{ $admin->created_at->format('d-m-Y') }}</td>
                    <td class="no-wrap">
                        @if ($admin->user->last_login_at)
                            {{ $admin->user->last_login_at->format('d-m-Y H:i') }}
                        @else
                            Belum pernah
                        @endif
                    </td>
                    <td>
                        @if ($admin->user->last_login_at && $admin->user->last_login_at->diffInDays(now()) < 30)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-warning">Tidak Aktif</span>
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
