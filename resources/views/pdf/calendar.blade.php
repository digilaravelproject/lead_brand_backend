<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Calendar</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 20px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        .page {
            page-break-after: always;
            position: relative;
            height: 100%;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #1e3a8a;
            font-size: 26px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .header p {
            margin: 2px 0 0 0;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }
        
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .calendar-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            padding: 10px 5px;
            border: 1px solid #cbd5e1;
            text-align: center;
            width: 14.28%;
        }
        .calendar-table td {
            border: 1px solid #cbd5e1;
            height: 72px;
            vertical-align: top;
            padding: 6px;
            font-weight: bold;
            color: #334155;
            font-size: 12px;
        }
        .calendar-table td.empty {
            background-color: #f8fafc;
        }
        
        /* Banner Card Table styling */
        .banner-table {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            border-collapse: collapse;
            background-color: #ffffff;
        }
        .banner-table td {
            border: none;
            padding: 0;
        }
        
        .banner-services {
            font-size: 8.5px;
            color: #1e3a8a;
            line-height: 1.4;
        }
        .banner-service-item {
            margin-bottom: 2.5px;
        }
        .bullet-icon {
            color: #3b82f6;
            font-size: 9px;
            margin-right: 3px;
        }
    </style>
</head>
<body>

    @foreach($calendarData as $index => $month)
        <div class="page">
            <!-- Calendar Header -->
            <div class="header">
                <h1>{{ $month['month_name'] }}</h1>
                <p>{{ $month['year'] }}</p>
            </div>
            
            <!-- Calendar Table Grid -->
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($month['weeks'] as $week)
                        <tr>
                            @foreach($week as $day)
                                @if($day === '')
                                    <td class="empty"></td>
                                @else
                                    <td>
                                        <div style="margin-bottom: 5px;">{{ $day }}</div>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Banner Card Container (Screenshot 1 Matching) -->
            <div style="margin-top: 15px;">
                <table class="banner-table" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 12px; background-color: #ffffff; table-layout: fixed;">
                    <tr>
                        <!-- Left: Profile Photo (25% width) -->
                        <td style="width: 25%; text-align: center; vertical-align: middle; background-color: #f1f5f9; border-right: 1px solid #cbd5e1; overflow: hidden;">
                            @if($userPhotoLocal)
                                <img src="{{ $userPhotoLocal }}" style="width: 100%; max-height: 180px; display: block; object-fit: cover;" />
                            @else
                                <div style="padding: 40px 10px; font-weight: bold; color: #94a3b8; font-size: 13px;">No Profile Image</div>
                            @endif
                        </td>
                        
                        <!-- Middle: User details & contact (48% width) -->
                        <td style="width: 48%; padding: 15px; vertical-align: top; text-align: left;">
                            <div style="color: #16a34a; font-style: italic; font-size: 11.5px; font-weight: bold; margin-bottom: 5px; font-family: Arial, sans-serif;">
                                {{ $bannerHeading }}
                            </div>
                            <div style="color: #1e3a8a; font-size: 20px; font-weight: bold; margin-bottom: 2px; text-transform: uppercase;">
                                {{ $user->name }}
                            </div>
                            <div style="color: #4b5563; font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 12px;">
                                {{ $user->destination ?? 'Financial Consultant' }}
                            </div>
                            
                            <table style="width: 100%; margin-top: 5px;">
                                <tr>
                                    <td style="width: 25px; padding-bottom: 8px; vertical-align: middle; color: #16a34a; font-size: 13px;">
                                        📞
                                    </td>
                                    <td style="color: #16a34a; font-size: 13px; font-weight: bold; padding-bottom: 8px; vertical-align: middle;">
                                        {{ $user->phone_number ?? 'Not Available' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 25px; vertical-align: middle; color: #4b5563; font-size: 13px;">
                                        ✉
                                    </td>
                                    <td style="color: #4b5563; font-size: 12px; vertical-align: middle;">
                                        {{ $user->email }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        
                        <!-- Right: Servicing consultancy & Logo (27% width) -->
                        <td style="width: 27%; padding: 15px 10px; vertical-align: top; text-align: left;">
                            <!-- Yellow Circle Logo -->
                            <div style="float: right; margin-left: 5px; margin-bottom: 5px;">
                                @if($userLogoLocal)
                                    <img src="{{ $userLogoLocal }}" style="width: 42px; height: 42px; border-radius: 50%; border: 2px solid #fbbf24;" />
                                @else
                                    <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #fbbf24; color: #ffffff; font-weight: bold; line-height: 42px; text-align: center; font-size: 10px;">LB</div>
                                @endif
                            </div>
                            
                            <div style="color: #1e3a8a; font-size: 11.5px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">
                                Servicing Consultancy
                            </div>
                            
                            <div class="banner-services">
                                @foreach($servicesArray as $service)
                                    <div class="banner-service-item">
                                        <span class="bullet-icon">💙</span> {{ $service }}
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Disclaimer Row -->
                    <tr>
                        <td colspan="3" style="font-size: 7px; color: #94a3b8; padding: 4px 15px; border-top: 1px solid #cbd5e1; background-color: #f8fafc; font-weight: normal; text-align: left;">
                            DISCLAIMER: The above concept has been developed after research by financial experts. Results are based on current bonus & FAB rates announced by respective company. For Premium budget, nearest sum assured has been taken. This is not a legal document.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach

</body>
</html>
