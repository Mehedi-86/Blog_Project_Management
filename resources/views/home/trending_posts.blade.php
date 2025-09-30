<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <style>
        .trending-container {
            width: 90%;
            max-width: 900px;
            margin: auto;
            font-family: Arial, sans-serif;
        }

        .trending-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 40px;
        }

        .post-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #ffc107; /* A gold color to indicate "trending" */
        }

        .post-card h4 {
            margin-top: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .badge-trending {
            background-color: #ffc107;
            color: #333;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="trending-container">
        <h1 class="trending-title">🔥 Trending Posts</h1>

        @forelse($trendingPosts as $post)
            <div class="post-card">
                <h4>
                    {{ $post->title }}
                    <span class="badge-trending">Trending</span>
                </h4>
                <p class="post-meta">
                    By <strong>{{ $post->author_name }}</strong> in <strong>{{ $post->category_name ?? 'Uncategorized' }}</strong>
                </p>
                {{-- You can add more post details here if you like, e.g., content snippet --}}
            </div>
        @empty
            <div class="text-center">
                <h3>No posts are trending right now.</h3>
                <p>Posts with 5 or more likes will appear here!</p>
            </div>
        @endforelse
    </div>

    @include('home.footer')
</body>
</html>

