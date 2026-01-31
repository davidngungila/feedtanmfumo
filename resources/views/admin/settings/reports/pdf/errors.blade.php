<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Error Reports - FeedTan CMG</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            border-bottom: 3px solid #015425;
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
        .logo-box {
            display: inline-block;
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 10px;
        }
        .header-info {
            font-size: 10pt;
            color: #666;
            margin-top: 8px;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            margin: 15px 0 10px 0;
        }
        .serial-number {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
        }
        .stats {
            display: table;
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            font-size: 8pt;
        }
        .stats-label {
            font-weight: bold;
            color: #015425;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 8pt;
        }
        th {
            background: #015425;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #015425;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .section {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .section-header {
            background: #015425;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 8px;
        }
        .section-content {
            padding: 8px 0;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            @if(isset($headerBase64) && $headerBase64)
            <img src="{{ $headerBase64 }}" alt="FeedTan Header" style="width: 100%; max-width: 100%; height: auto; display: block; margin: 0 auto;">
            @else
            <div class="logo-box" style="margin: 0 auto 10px auto;">FD</div>
            @endif
        </div>
        <div class="title">{{ $documentTitle ?? 'Error Reports' }}</div>
        <div class="serial-number">Serial No: FCMGER-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Error Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Errors:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_errors']) }}</strong></div>
            <div class="stats-cell stats-label">Errors Today:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['errors_today']) }}</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Errors This Week:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['errors_this_week']) }}</strong></div>
            <div class="stats-cell stats-label">Errors This Month:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['errors_this_month']) }}</strong></div>
        </div>
    </div>

    <!-- Error Details -->
    @if($errors->count() > 0)
    <div class="section">
        <div class="section-header">Failed Job Errors</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Queue</th>
                        <th>Exception</th>
                        <th>Failed At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($errors as $error)
                    <tr>
                        <td style="font-family: 'Courier New', monospace;">#{{ $error->id }}</td>
                        <td>{{ $error->queue ?? 'default' }}</td>
                        <td style="font-size: 7pt;">{{ Str::limit($error->exception ?? 'N/A', 60) }}</td>
                        <td>{{ $error->failed_at ? \Carbon\Carbon::parse($error->failed_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="section">
        <div class="section-header">Failed Job Errors</div>
        <div class="section-content">
            <p style="text-align: center; padding: 20px; color: #666;">No errors found</p>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Error Reports</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGER-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>

