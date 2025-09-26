<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic -->
    @include('home.homecss')
    <style>
        .container-notifications {
            max-width: 900px;
            margin: 20px auto 50px;
            padding: 0 15px;
        }

        .header_section {
            position: relative; /* or sticky if needed */
            z-index: 9999; /* higher than any content below */
        }

        h1.page-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            position: relative;
        }

        h1.page-title::after {
            content: "";
            display: block;
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            margin: 12px auto 0;
            border-radius: 3px;
        }

        .notification-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px 25px;
            margin-bottom: 20px;
            transition: transform 0.2s ease-in-out;
        }

        .notification-card:hover {
            transform: translateY(-3px);
        }

        .notification-card p {
            margin: 0;
            font-size: 1.1rem;
            color: #444;
        }
        .notification-card {
            position: relative; /* for absolute positioning of trash */
            padding-right: 50px; /* avoid overlap with trash */
        }

        .notification-time {
            font-size: 0.85rem;
            color: #888;
            margin-top: 5px;
        }

        .no-notifications {
            text-align: center;
            margin-top: 50px;
            font-size: 1.3rem;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- header section start -->
    <div class="header_section">
        @include('home.header')
    </div>
    <!-- header section end -->

    <!-- Notifications Section Start -->
    <div class="container-notifications">
        <h1 class="page-title">Your Notifications</h1>

        @if(count($notifications) === 0)
            <div class="no-notifications">You have no notifications yet!</div>
        @else
                    @foreach($notifications as $notif)
                <div class="notification-card" style="position: relative;">
                    @if($notif->type === 'like')
                        <p>💖 {{ $notif->liked_by_name }} liked your post "{{ $notif->post_title }}"</p>
                    @elseif($notif->type === 'comment')
                        <p>💬 {{ $notif->commented_by_name }} commented on your post "{{ $notif->post_title }}"</p>
                    @else
                        <p>ℹ️ {{ $notif->type }} notification</p>
                    @endif

                    <div class="notification-time">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</div>

                    <!-- Trash button -->
                    <form action="{{ route('deleteNotification', $notif->id) }}" method="POST" style="position:absolute; top:15px; right:15px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="border:none; background:none; cursor:pointer; color:#dc3545; font-size:1.2rem;">
                            🗑️
                        </button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
    <!-- Notifications Section End -->

    @include('home.footer')
</body>
</html>
