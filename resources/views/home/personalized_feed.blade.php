<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Includes your existing homecss --}}
    @include('home.homecss') 
    
    {{-- We will use the 'Inter' font for a modern look --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .feed-container {
            width: 90%;
            max-width: 1200px;
            margin:  auto;
        }

        .feed-title-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .feed-title {
            font-size: 32px;
            font-weight: 700;
            color: #222;
            margin-bottom: 8px;
        }

        .feed-subtitle {
            font-size: 18px;
            color: #555;
        }

        /* Responsive Post Grid */
        .post-grid {
            display: grid;
            /* This creates a responsive grid. Cards will be 350px wide, 
               and it will fit as many as possible per row. */
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }

        /* The Post Card */
        .post-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .post-card-content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Makes card fill height */
        }

        .post-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .post-author {
            font-size: 15px;
            font-weight: 500;
            color: #333;
        }

        /* Relevance Badge */
        .relevance-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        /* Badge Colors based on Relevance */
        .badge-follow { background-color: #e0f2fe; color: #0284c7; } /* Blue */
        .badge-trending { background-color: #fff1f2; color: #e11d48; } /* Red */
        .badge-category { background-color: #ecfdf5; color: #059669; } /* Green */
        .badge-popular { background-color: #f3f4f6; color: #4b5563; } /* Gray */

        .post-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 12px;
            /* This is a direct link, better for SEO and accessibility than onclick */
            text-decoration: none;
            transition: color 0.2s;
        }

        .post-title:hover {
            color: #4facfe;
        }

        .post-snippet {
            font-size: 15px;
            color: #4a5567;
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1; /* Pushes footer down */
        }

        .post-card-footer {
            border-top: 1px solid #eef2f7;
            padding-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #718096;
        }
        
        .post-category {
            font-weight: 500;
            color: #333;
        }

        /* "No Posts" Message */
        .no-posts-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 60px 40px;
            text-align: center;
            margin: 40px auto;
        }

        .no-posts-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
        }

        .no-posts-text {
            font-size: 16px;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="feed-container">
        <div class="feed-title-container">
            <div class="feed-title">🎯 For You</div>
            <div class="feed-subtitle">A personalized feed based on your activity.</div>
        </div>

        @if($posts && count($posts) > 0)
            <div class="post-grid">
                @foreach($posts as $post)
                    <div class="post-card">
                        <div class="post-card-content">
                            <div class="post-card-header">
                                <span class="post-author">By {{ $post->author_name }}</span>
                                
                                {{-- Here we use Blade directives to show the reason --}}
                                @php
                                    $badgeClass = 'badge-popular'; // Default
                                    $badgeText = 'Popular';
                                    if ($post->relevance_score == 100) {
                                        $badgeClass = 'badge-follow';
                                        $badgeText = 'Following';
                                    } elseif ($post->relevance_score == 90) {
                                        $badgeClass = 'badge-trending';
                                        $badgeText = 'Trending';
                                    } elseif ($post->relevance_score == 80) {
                                        $badgeClass = 'badge-category';
                                        $badgeText = 'Favorite Topic';
                                    }
                                @endphp
                                <span class="relevance-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>
                            
                            {{-- This link is better than an onclick row --}}
                            <a href="#" class="post-title" onclick="event.stopPropagation(); window.location='{{ route('post.details', $post->id) }}'; return false;">
                                {{ $post->title }}
                            </a>

                            <p class="post-snippet">
                                {{-- Added a content snippet, which is common for feeds --}}
                                {{ \Illuminate\Support\Str::limit($post->content, 120) }}
                            </p>

                            <div class="post-card-footer">
                                <span class="post-category">{{ $post->category_name ?? 'General' }}</span>
                                <span class="post-date">
                                    {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-posts-container">
                <div class="no-posts-title">Your feed is looking a bit empty!</div>
                <p class="no-posts-text">Try following some new users or liking posts in topics you enjoy to get personalized recommendations.</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    @include('home.footer')
</body>
</html>

