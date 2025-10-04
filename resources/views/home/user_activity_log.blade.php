<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic -->
    @include('home.homecss')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
        }

        .activity-log-container {
            padding: 50px 15px;
        }

        .activity-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }

        /* Summary Section Styles */
        .summary-section {
            max-width: 900px;
            margin: 0 auto 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .summary-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 30px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            text-align: center;
        }

        .summary-item {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .summary-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .summary-item h3 {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .summary-item p {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .summary-item small {
            display: block;
            margin-top: 5px;
            color: #6c757d;
            font-weight: 500;
        }

        .no-activity-message {
            text-align: center;
            padding: 40px;
            font-size: 1.2rem;
            color: #6c757d;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: #e0e0e0;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            top: 20px;
            right: -8px;
            z-index: 1;
            border: 4px solid currentColor; /* Dot matches activity color */
            background-color: #fff;
        }

        .left { left: 0; }
        .right { left: 50%; }

        .left::before {
            content: "";
            height: 0;
            position: absolute;
            top: 28px;
            width: 0;
            z-index: 1;
            right: 30px;
            border: medium solid white;
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent white;
        }

        .right::before {
            content: "";
            height: 0;
            position: absolute;
            top: 28px;
            width: 0;
            z-index: 1;
            left: 30px;
            border: medium solid white;
            border-width: 10px 10px 10px 0;
            border-color: transparent white transparent transparent;
        }

        .right::after { left: -8px; }

        .content {
            padding: 20px 30px;
            background-color: white;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .content:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .content h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .content p {
            margin: 0 0 10px 0;
            color: #555;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .content .time {
            font-size: 0.8rem;
            color: #888;
            font-weight: 500;
        }

        @media screen and (max-width: 600px) {
            .timeline::after { left: 20px; }
            .timeline-item { width: 100%; padding-left: 60px; padding-right: 15px; }
            .timeline-item::before { left: 50px; border-width: 10px 10px 10px 0; border-color: transparent white transparent transparent; }
            .left::after, .right::after { left: 10px; }
            .right { left: 0%; }
        }
    </style>
</head>
<body>
    <!-- header section start -->
    <div class="header_section">
        @include('home.header')
    </div>
    <!-- header section end -->

    <div class="activity-log-container">
        <h1 class="activity-title">My Activity Log</h1>

        <!-- Summary Section -->
        <div class="summary-section">
            <h2 class="summary-title">Activity At a Glance (Last 7 Days)</h2>
            @if(count($dailySummary) > 0)
                <div class="summary-grid">
                    @foreach($dailySummary as $day)
                        <div class="summary-item">
                            <h3>{{ \Carbon\Carbon::parse($day['activity_date'])->format('M d, Y') }}</h3>
                            <p>{{ $day['action_count'] }} Actions</p>
                            @if($day['actions'])
                                <small>
                                    @foreach($day['actions'] as $action)
                                        • {{ $action }}<br>
                                    @endforeach
                                </small>
                            @endif
                            @if($day['session_duration'])
                                <small>({{ $day['session_duration'] }})</small>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-activity-message">
                    <p>No activity recorded in the last 7 days.</p>
                </div>
            @endif
        </div>


        <!-- Detailed Timeline Section -->
        @if(count($activities) > 0)
            <h2 class="summary-title">Recent Activity Timeline</h2>
            <div class="timeline">
                @foreach($activities as $key => $activity)
                    @php
                        $color = match($activity->activity_type) {
                            'Created a Post' => '#28a745',
                            'Liked a Post' => '#007bff',
                            'Commented on a Post' => '#fd7e14',
                            'Saved a Post' => '#6f42c1',
                            'Followed a User' => '#20c997',
                            default => '#343a40',
                        };
                    @endphp
                    <div class="timeline-item {{ $key % 2 == 0 ? 'left' : 'right' }}">
                        <div class="content" style="border-left: 4px solid {{ $color }}; color: {{ $color }}">
                            <h2>{{ $activity->activity_type }}</h2>
                            <p>
                                @php
                                    $details = Str::of($activity->details)->limit(70);
                                @endphp
                                "{{ $details }}{{ Str::length($activity->details) > 70 ? '...' : '' }}"
                            </p>
                            <span class="time">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @include('home.footer')
</body>
</html>


<!-- 
================================================================================
MY ACTIVITY LOG PAGE EXPLANATION
================================================================================

1. PAGE HEADER
- Page title: "My Activity Log" is shown at the top center.

2. SUMMARY SECTION (Activity At a Glance - Last 7 Days)
- Purpose: Quick overview of recent activity.
- Shows for each of the last 7 days:
    1. Date (e.g., Sep 28, 2025)
    2. Action Count: Total actions that day (posts, comments, likes, saved posts)
    3. Session Duration: Time between first and last activity (e.g., 3 hours)
- Visual: Each day is a card (.summary-item) with hover effects.
- If no activity in last 7 days, shows: "No activity recorded in the last 7 days."

3. DETAILED TIMELINE SECTION (Recent Activity Timeline)
- Purpose: Shows each action in chronological order (most recent first)
- Structure: Vertical timeline with items alternating left/right
- Each timeline item shows:
    1. Activity Type (e.g., "Created a Post", "Liked a Post")
    2. Activity Details (truncated if too long)
    3. Time Ago (e.g., "2 hours ago")
- Visuals:
    - Colored dot on timeline for activity type
    - Timeline line in the center connects items
    - Cards have hover shadows
    - Responsive design for mobile devices


USER EXPERIENCE
- Summary cards give quick glance at daily activity
- Timeline shows detailed actions with relative times
- Color-coded and visually organized for easy scanning

-->
