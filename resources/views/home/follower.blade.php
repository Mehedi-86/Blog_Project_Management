<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <style>
        .header_section {
            position: relative;
            z-index: 9999;
        }
        .followers-container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .followers-title {
        text-align: center;
        font-size: 3rem; /* much bigger */
        font-weight: 800;
        color: #2c3e50;
        margin-bottom: 30px;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
    }

    /* Add a gradient underline effect */
    .followers-title::after {
        content: "";
        display: block;
        width: 120px;
        height: 6px;
        background: linear-gradient(90deg, #007bff, #00c6ff);
        margin: 12px auto 0;
        border-radius: 3px;
    }
        .follower-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        .follower-item:last-child {
            border-bottom: none;
        }
        .follower-name {
            font-size: 1.1rem;
            color: #2c3e50;
            font-weight: 500;
        }
        .btn-follow {
            padding: 6px 14px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-follow.follow {
            background: #007bff;
            color: #fff;
        }
        .btn-follow.unfollow {
            background: #dc3545;
            color: #fff;
        }
        .btn-follow:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="followers-container">
     <h1 class="followers-title">Followers</h1>

        @foreach($allUsers as $u)
            <div class="follower-item">
                <div class="follower-name">{{ $u->name }}</div>

                @if(in_array($u->id, $followingIds))
                    <!-- Unfollow Button -->
                    <form action="{{ route('unfollowUser', $u->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-follow unfollow">Unfollow</button>
                    </form>
                @else
                    <!-- Follow Button -->
                    <form action="{{ route('followUser', $u->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-follow follow">Follow</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

    @include('home.footer')
</body>
</html>
