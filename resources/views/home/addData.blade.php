<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 15px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 50px 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .card h2 {
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: #333;
        }

        .card .btn-add-post {
            display: block; /* ensures vertical stacking */
            margin-bottom: 15px; /* space between buttons */
        }


        .btn-add-post {
            display: inline-block;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 12px 35px;
            border-radius: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-add-post:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        @media (max-width: 576px) {
            .card {
                padding: 30px 20px;
            }
            .btn-add-post {
                padding: 10px 25px;
            }
        }
    </style>
</head>
<body>

    <!-- header section -->
    <div class="header_section">
        @include('home.header')
    </div>

    <!-- Main Add Post Button -->
    <div class="main-container">
    <div class="card">
        <h2>Insert Data</h2>
        <a href="{{ route('addPost') }}" class="btn-add-post mb-3">➕ Add Post</a>
        <a href="{{ route('likePostPage') }}" class="btn-add-post">❤️ Post Operations</a>
        <a href="{{ route('commentPostPage') }}" class="btn-add-post mb-3">💬 Comment on Post</a>
        <a href="{{ route('showNotifications') }}" class="btn-add-post">🔔 Show Notifications</a>
        <a href="{{ route('followerPage') }}" class="btn-add-post">👥 Followers</a>
    </div>
</div>

    @include('home.footer')
</body>
</html>
