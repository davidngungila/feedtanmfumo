<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Reports - FeedTan CMG</title>
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
        <div class="title">{{ $documentTitle ?? 'System Reports' }}</div>
        <div class="serial-number">Serial No: FCMGSR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- System Information -->
    <div class="section">
        <div class="section-header">System Information</div>
        <div class="section-content">
            <div class="stats">
                <div class="stats-row">
                    <div class="stats-cell stats-label">Laravel Version:</div>
                    <div class="stats-cell"><strong>{{ $systemInfo['laravel_version'] }}</strong></div>
                    <div class="stats-cell stats-label">PHP Version:</div>
                    <div class="stats-cell"><strong>{{ $systemInfo['php_version'] }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Environment:</div>
                    <div class="stats-cell"><strong>{{ ucfirst($systemInfo['environment']) }}</strong></div>
                    <div class="stats-cell stats-label">Database Driver:</div>
                    <div class="stats-cell"><strong>{{ ucfirst($systemInfo['database_driver']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Timezone:</div>
                    <div class="stats-cell"><strong>{{ $systemInfo['timezone'] }}</strong></div>
                    <div class="stats-cell stats-label">Server Time:</div>
                    <div class="stats-cell"><strong>{{ $systemInfo['server_time'] }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="section">
        <div class="section-header">User Statistics</div>
        <div class="section-content">
            <div class="stats">
                <div class="stats-row">
                    <div class="stats-cell stats-label">Total Users:</div>
                    <div class="stats-cell"><strong>{{ number_format($userStats['total_users']) }}</strong></div>
                    <div class="stats-cell stats-label">Active Users:</div>
                    <div class="stats-cell"><strong>{{ number_format($userStats['active_users']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Members:</div>
                    <div class="stats-cell"><strong>{{ number_format($userStats['members']) }}</strong></div>
                    <div class="stats-cell stats-label">Administrators:</div>
                    <div class="stats-cell"><strong>{{ number_format($userStats['admins']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">New Users Today:</div>
                    <div class="stats-cell"><strong>{{ number_format($userStats['new_users_today']) }}</strong></div>
                    <div class="stats-cell stats-label">New Users This Week:</div>
                    <div class="stats-cell"><strong>{{ number_format($userStats['new_users_this_week']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">New Users This Month:</div>
                    <div class="stats-cell"><strong>{{ number_format($userStats['new_users_this_month']) }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Statistics -->
    <div class="section">
        <div class="section-header">Login Statistics</div>
        <div class="section-content">
            <div class="stats">
                <div class="stats-row">
                    <div class="stats-cell stats-label">Total Logins:</div>
                    <div class="stats-cell"><strong>{{ number_format($loginStats['total_logins']) }}</strong></div>
                    <div class="stats-cell stats-label">Logins Today:</div>
                    <div class="stats-cell"><strong>{{ number_format($loginStats['logins_today']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Logins This Week:</div>
                    <div class="stats-cell"><strong>{{ number_format($loginStats['logins_this_week']) }}</strong></div>
                    <div class="stats-cell stats-label">Logins This Month:</div>
                    <div class="stats-cell"><strong>{{ number_format($loginStats['logins_this_month']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Active Sessions:</div>
                    <div class="stats-cell"><strong>{{ number_format($loginStats['active_sessions']) }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Statistics -->
    <div class="section">
        <div class="section-header">Database Statistics</div>
        <div class="section-content">
            <div class="stats">
                <div class="stats-row">
                    <div class="stats-cell stats-label">Total Loans:</div>
                    <div class="stats-cell"><strong>{{ number_format($dbStats['total_loans']) }}</strong></div>
                    <div class="stats-cell stats-label">Savings Accounts:</div>
                    <div class="stats-cell"><strong>{{ number_format($dbStats['total_savings_accounts']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Investments:</div>
                    <div class="stats-cell"><strong>{{ number_format($dbStats['total_investments']) }}</strong></div>
                    <div class="stats-cell stats-label">Transactions:</div>
                    <div class="stats-cell"><strong>{{ number_format($dbStats['total_transactions']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Welfare Records:</div>
                    <div class="stats-cell"><strong>{{ number_format($dbStats['total_welfare_records']) }}</strong></div>
                    <div class="stats-cell stats-label">Issues:</div>
                    <div class="stats-cell"><strong>{{ number_format($dbStats['total_issues']) }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit & Activity Statistics -->
    <div class="section">
        <div class="section-header">Audit & Activity Statistics</div>
        <div class="section-content">
            <div class="stats">
                <div class="stats-row">
                    <div class="stats-cell stats-label">Total Audit Logs:</div>
                    <div class="stats-cell"><strong>{{ number_format($auditStats['total_audit_logs']) }}</strong></div>
                    <div class="stats-cell stats-label">Audit Logs Today:</div>
                    <div class="stats-cell"><strong>{{ number_format($auditStats['audit_logs_today']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Audit Logs This Week:</div>
                    <div class="stats-cell"><strong>{{ number_format($auditStats['audit_logs_this_week']) }}</strong></div>
                    <div class="stats-cell stats-label">Total Activity Logs:</div>
                    <div class="stats-cell"><strong>{{ number_format($auditStats['total_activity_logs']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Activity Logs Today:</div>
                    <div class="stats-cell"><strong>{{ number_format($auditStats['activity_logs_today']) }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Statistics -->
    <div class="section">
        <div class="section-header">Error Statistics</div>
        <div class="section-content">
            <div class="stats">
                <div class="stats-row">
                    <div class="stats-cell stats-label">Total Errors:</div>
                    <div class="stats-cell"><strong>{{ number_format($errorStats['total_errors']) }}</strong></div>
                    <div class="stats-cell stats-label">Errors Today:</div>
                    <div class="stats-cell"><strong>{{ number_format($errorStats['errors_today']) }}</strong></div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell stats-label">Errors This Week:</div>
                    <div class="stats-cell"><strong>{{ number_format($errorStats['errors_this_week']) }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logins -->
    @if($recentLogins->count() > 0)
    <div class="section">
        <div class="section-header">Recent Login Sessions</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Login At</th>
                        <th>Logout At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogins as $login)
                    <tr>
                        <td>{{ $login->user->name ?? 'N/A' }}</td>
                        <td style="font-family: 'Courier New', monospace;">{{ $login->ip_address ?? 'N/A' }}</td>
                        <td style="font-size: 7pt;">{{ Str::limit($login->user_agent ?? 'N/A', 40) }}</td>
                        <td>{{ $login->login_at ? \Carbon\Carbon::parse($login->login_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                        <td>{{ $login->logout_at ? \Carbon\Carbon::parse($login->logout_at)->format('Y-m-d H:i') : 'Active' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Audit Logs -->
    @if($recentAudits->count() > 0)
    <div class="section">
        <div class="section-header">Recent Audit Logs</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentAudits as $audit)
                    <tr>
                        <td>{{ $audit->user_name ?? 'System' }}</td>
                        <td>{{ ucfirst($audit->action ?? 'N/A') }}</td>
                        <td>{{ $audit->model_type ?? 'N/A' }}</td>
                        <td style="font-family: 'Courier New', monospace;">{{ $audit->ip_address ?? 'N/A' }}</td>
                        <td>{{ $audit->created_at ? \Carbon\Carbon::parse($audit->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Recent Activities -->
    @if(isset($recentActivities) && $recentActivities->count() > 0)
    <div class="section">
        <div class="section-header">Recent System Activities</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivities as $activity)
                    <tr>
                        <td>{{ $activity->user_name ?? 'System' }}</td>
                        <td>{{ ucfirst($activity->action ?? 'N/A') }}</td>
                        <td>{{ Str::limit($activity->description ?? 'N/A', 50) }}</td>
                        <td>{{ $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>FeedTan Community Microfinance Group - System Reports</p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Serial No: FCMGSR-{{ date('Ymd') }}-{{ str_pad(1, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
