<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')

    <style>
        /* Section styling */
        .operations-section {
            text-align: center;
            padding: 40px 15px; /* Adds consistent spacing around the content */
            background-color: #f8f9fa; /* A light background for the main area */
        }

        .operations-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px; /* Increased space below the title */
        }

        /* Button styling for vertical alignment */
        .stats-button {
            display: block; /* This is the key change: makes the <a> tag a block element */
            max-width: 500px; /* Sets a maximum width for the buttons */
            margin: 0 auto 15px auto; /* Centers the buttons and adds 15px of space below each one */
            padding: 16px 20px; /* Adjusted padding for a better look */
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
            box-shadow: 0 8px 22px rgba(0,0,0,0.25); /* Slightly enhanced shadow on hover */
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
        
        <!-- The buttons will now stack vertically because of 'display: block' -->
        <a href="{{ route('users.list') }}" class="stats-button">
            Total Users: {{ $totalUsers }}
        </a>
        <a href="{{ route('posts.list') }}" class="stats-button">
            Total Posts: {{ $totalPosts }}
        </a>
        <a href="{{ route('posts.liked') }}" class="stats-button">
            Posts I Liked
        </a>
        <a href="{{ route('posts.commented') }}" class="stats-button">
            Posts I Commented
        </a>
        <a href="{{ route('posts.byMe') }}" class="stats-button">
            Posts By Me
        </a>
        <a href="{{ route('posts.savedByMe') }}" class="stats-button">
            Posts I Saved
        </a>
        <a href="{{ route('followers.ofMe') }}" class="stats-button">
            Followers of Me
        </a>
        <a href="{{ route('whom.iFollow') }}" class="stats-button">
            Whom I Follow
        </a>
        <a href="{{ route('posts.details') }}" class="stats-button">
            Post Details
        </a>
        <a href="{{ route('posts.active') }}" class="stats-button">
            Active Posts
        </a>
        <a href="{{ route('posts.rejected') }}" class="stats-button">
             Rejected Posts
        </a>
        <a href="{{ route('reports.my_posts') }}" class="stats-button">
            Who Reported On My Posts
        </a>
        <a href="{{ route('reports.by_me') }}" class="stats-button">
            Posts Reported By Me
        </a>
        <a href="{{ route('users.show') }}" class="stats-button">
            Show Active and Banned Users
        </a>
        <a href="{{ route('posts.trending') }}" class="stats-button">
            🔥 Trending Posts
        </a>
        <a href="{{ route('posts.analytics') }}" class="stats-button">
            My Post Analytics
        </a>
        <a href="{{ route('followers.mutual') }}" class="stats-button">
            My Connections (Mutuals)
        </a>
        <a href="{{ route('activity.log') }}" class="stats-button">
            My Activity Log
        </a>
        <a href="{{ route('activity.analysis') }}" class="stats-button">
            📊 My Activity Analysis
        </a>
    </div>

    @include('home.footer')
</body>
</html>
