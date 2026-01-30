<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', $title ?? 'Document') - FeedTan CMG</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap');
        
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Quicksand', 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1a1a1a;
            background: #ffffff;
        }
        
        /* Header Styles */
        .document-header {
            border-bottom: 3px solid #015425;
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
        
        .header-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 15px auto;
        }
        
        .document-title {
            font-size: 18pt;
            font-weight: bold;
            color: #015425;
            margin: 15px 0 10px 0;
            text-align: center;
        }
        
        .document-subtitle {
            font-size: 10pt;
            color: #666;
            margin-top: 5px;
            text-align: center;
        }
        
        /* Content Area */
        .document-content {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        
        /* Footer Styles */
        .document-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        
        .footer-info {
            margin: 3px 0;
        }
        
        /* Common Table Styles */
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
        
        /* Section Styles */
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
        
        /* Stats/Summary Boxes */
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
        
        /* Status Badges */
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-bold {
            font-weight: bold;
        }
        
        .text-green {
            color: #015425;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mt-10 {
            margin-top: 10px;
        }
        
        /* Page Break Controls */
        .page-break-before {
            page-break-before: always;
        }
        
        .page-break-after {
            page-break-after: always;
        }
        
        .no-break {
            page-break-inside: avoid;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Document Header -->
    <div class="document-header">
        @if(isset($headerBase64) && $headerBase64)
        <img src="{{ $headerBase64 }}" alt="FeedTan Header" class="header-image">
        @else
        <div style="background: #015425; color: white; padding: 8px 12px; font-weight: bold; font-size: 14pt; margin: 0 auto 10px auto; display: inline-block;">FD</div>
        @endif
        
        @if(isset($documentTitle))
        <div class="document-title">{{ $documentTitle }}</div>
        @endif
        
        @if(isset($documentSubtitle))
        <div class="document-subtitle">{{ $documentSubtitle }}</div>
        @endif
        
        @if(isset($generatedAt))
        <div class="document-subtitle">Generated: {{ $generatedAt }}</div>
        @endif
    </div>

    <!-- Document Content -->
    <div class="document-content">
        @yield('content')
    </div>

    <!-- Document Footer -->
    <div class="document-footer">
        <div class="footer-info">
            <strong>FeedTan Community Microfinance Group</strong>
        </div>
        <div class="footer-info">
            P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania
        </div>
        <div class="footer-info">
            Email: feedtan15@gmail.com | Phone: +255622239304
        </div>
        @if(isset($footerInfo))
        <div class="footer-info" style="margin-top: 8px;">
            {{ $footerInfo }}
        </div>
        @endif
        <div class="footer-info" style="margin-top: 8px; font-size: 6pt;">
            Document generated on {{ now()->format('F d, Y \a\t H:i:s') }}
        </div>
    </div>
</body>
</html>

