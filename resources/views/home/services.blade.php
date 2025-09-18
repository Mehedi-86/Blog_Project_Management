<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')

    <style>
        /* Section styling */
        .operations-section {
            text-align: center;
            margin:  0;
        }

        .operations-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
        }

        /* Button styling */
        .stats-button {
            display: inline-block;
            padding: 16px 200px; /* More left/right padding */
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        }

        .stats-button:hover {
            color: #fff;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <!-- header section start -->
    <div class="header_section">
        @include('home.header')
    </div>
    <!-- header section end -->

    <!-- Operations Section -->
    <div class="operations-section">
        <div class="operations-title">Lists of Operations</div>
        <a href="{{ route('users.list') }}" class="stats-button">
            Total Users: {{ $totalUsers }}
        </a>
    </div>

    @include('home.footer')
</body>
</html>
