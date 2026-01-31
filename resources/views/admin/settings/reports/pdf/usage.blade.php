<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Usage Statistics Report - FeedTan CMG</title>
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
        <div class="title">{{ $documentTitle ?? 'Usage Statistics Report' }}</div>
        <div class="serial-number">Serial No: FCMGUSR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Usage Summary Stats -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Users:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_users']) }}</strong></div>
            <div class="stats-cell stats-label">Active Users:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['active_users']) }}</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Logins Today:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_logins_today']) }}</strong></div>
            <div class="stats-cell stats-label">Logins This Week:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_logins_this_week']) }}</strong></div>
        </div>
        <div class="stats-row">
            <div class="stats-cell stats-label">Logins This Month:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_logins_this_month']) }}</strong></div>
            <div class="stats-cell stats-label">Total Audit Logs:</div>
            <div class="stats-cell"><strong>{{ number_format($stats['total_audit_logs']) }}</strong></div>
        </div>
    </div>

    <!-- Daily Login Trends -->
    @if($dailyLogins->count() > 0)
    <div class="section">
        <div class="section-header">Daily Login Trends (Last 30 Days)</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="text-align: right;">Login Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyLogins as $day)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                        <td style="text-align: right;"><strong>{{ number_format($day->count) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Top Active Users -->
    @if(isset($topActiveUsers) && $topActiveUsers->count() > 0)
    <div class="section">
        <div class="section-header">Top Active Users</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="text-align: right;">Login Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topActiveUsers->take(50) as $index => $user)
                    <tr>
                        <td>#{{ $index + 1 }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->status) }}</td>
                        <td style="text-align: right;"><strong>{{ number_format($user->login_count) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Login Sessions -->
    @if(isset($recentLogins) && $recentLogins->count() > 0)
    <div class="section">
        <div class="section-header">Recent Login Sessions</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Login At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogins->take(50) as $login)
                    <tr>
                        <td>{{ $login->user->name ?? 'N/A' }}</td>
                        <td style="font-family: 'Courier New', monospace;">{{ $login->ip_address ?? 'N/A' }}</td>
                        <td>{{ $login->login_at ? \Carbon\Carbon::parse($login->login_at)->format('M d, Y H:i') : 'N/A' }}</td>
                        <td>{{ $login->logout_at ? 'Logged Out' : 'Active' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- User Activity Breakdown -->
    @if(isset($userActivityBreakdown) && $userActivityBreakdown->count() > 0)
    <div class="section">
        <div class="section-header">User Activity Breakdown</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th style="text-align: right;">Total Logins</th>
                        <th>Last Login</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userActivityBreakdown->take(50) as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}</td>
                        <td style="text-align: right;"><strong>{{ number_format($user->total_logins) }}</strong></td>
                        <td>{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('M d, Y H:i') : 'Never' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Monthly Registration Trend -->
    @if(isset($monthlyRegistrations) && $monthlyRegistrations->count() > 0)
    <div class="section">
        <div class="section-header">Monthly User Registration Trend (Last 12 Months)</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th style="text-align: right;">New Users</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyRegistrations as $month)
                    <tr>
                        <td>{{ \Carbon\Carbon::create($month->year, $month->month, 1)->format('F Y') }}</td>
                        <td style="text-align: right;"><strong>{{ number_format($month->count) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>FeedTan Community Microfinance Group - Usage Statistics Report</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGUSR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>

