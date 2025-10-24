<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    
    {{-- Using a modern, readable font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* A slight off-white for the background */
        }

        .post-detail-wrapper {
            width: 90%;
            max-width: 1100px;
            margin: auto;
            display: grid;
            /* Creates a 70% / 30% split, with a 40px gap */
            grid-template-columns: 3fr 1fr;
            gap: 40px;
        }

        /* Main Post Content Area */
        .post-body {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.07);
            overflow: hidden; /* Ensures rounded corners are respected */
        }
        
        .post-header {
            padding: 40px 40px 25px 40px;
            border-bottom: 1px solid #f0f0f0;
        }

        .post-category-tag {
            display: inline-block;
            padding: 6px 14px;
            background-color: #e0f2fe; /* Light blue */
            color: #0284c7; /* Dark blue */
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .post-title-main {
            font-size: 38px;
            font-weight: 700;
            color: #1a202c;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .post-meta-info {
            font-size: 15px;
            color: #555;
        }

        .post-meta-info strong {
            color: #000;
            font-weight: 600;
        }

        .post-body-content {
            padding: 30px 40px 40px 40px;
            font-size: 17px;
            color: #333;
            line-height: 1.7; /* Increased line height for readability */
        }
        
        /* Sidebar */
        .post-sidebar {
            /* The sidebar will just be a container for widgets */
        }

        .sidebar-widget {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 30px;
        }

        .widget-title {
            font-size: 18px;
            font-weight: 600;
            color: #222;
            border-bottom: 1px solid #eee;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .author-name {
            font-size: 16px;
            font-weight: 600;
            color: #111;
            margin-bottom: 8px;
        }

        .author-bio {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
        }
        
        .share-links a {
            display: block;
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
            font-size: 15px;
            padding: 8px 0;
            transition: color 0.2s;
        }

        .share-links a:hover {
            color: #0056b3;
        }

        .back-link {
            display: inline-block;
            text-decoration: none;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 20px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(79, 172, 254, 0.4);
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(79, 172, 254, 0.5);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .post-detail-wrapper {
                /* Stacks the sidebar on top of the content on mobile */
                grid-template-columns: 1fr;
            }
            .post-sidebar {
                order: -1; /* Moves sidebar to the top */
            }
        }

    </style>
</head>
<body>
    <!-- Header -->
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="post-detail-wrapper">
        
        <!-- MAIN POST CONTENT -->
        <main class="post-body">
            <header class="post-header">
                <div class="post-category-tag">
                    {{ $post->category_name ?? 'General' }}
                </div>
                <h1 class="post-title-main">{{ $post->title }}</h1>
                <div class="post-meta-info">
                    By <strong>{{ $post->author_name }}</strong> &bull; 
                    Published on {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}
                </div>
            </header>

            <article class="post-body-content">
                {{-- This backend logic is identical. It safely displays content and respects line breaks. --}}
                {!! nl2br(e($post->content)) !!}
            </article>
        </main>

        <!-- SIDEBAR -->
        <aside class="post-sidebar">
            
            {{-- Back Button --}}
            <a href="{{ url()->previous() }}" class="back-link" style="margin-top: 30px; margin-bottom: 30px;">← Back to Feed</a>

            {{-- Author Widget --}}
            <div class="sidebar-widget">
                <h3 class="widget-title">About the Author</h3>
                <div class="author-name">{{ $post->author_name }}</div>
                <p class="author-bio">
                    (This is where a short user bio would go. i can add a 'bio' column to my 'users' table to display it here!)
                </p>
            </div>
            
            {{-- Share Widget --}}
            <div class="sidebar-widget">
                <h3 class="widget-title">Share this Post</h3>
                <div class="share-links">
                    {{-- These are placeholder links, but are styled professionally --}}
                    <a href="#">Share on Twitter</a>
                    <a href="#">Share on Facebook</a>
                    <a href="#">Share on LinkedIn</a>
                </div>
            </div>

        </aside>

    </div>

    <!-- Footer -->
    @include('home.footer')
</body>
</html>
