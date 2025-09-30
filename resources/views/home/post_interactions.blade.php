<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f7f6;
        }
        .interactions-container {
            width: 95%;
            max-width: 1100px;
            margin: auto;
            font-family: Arial, sans-serif;
        }
        .page-title { 
            text-align: center; 
            font-size: 2.5rem; 
            margin-bottom: 40px; 
            color: #333; 
            font-weight: 700;
        }
        /* New Grid Layout */
        .interactions-grid {
            display: grid;
            grid-template-columns: 1fr; /* Default to single column */
            gap: 30px;
        }
        /* Media query for two columns on larger screens */
        @media (min-width: 768px) {
            .interactions-grid {
                grid-template-columns: 1fr 2fr; /* Likes take 1/3, Comments take 2/3 */
            }
        }

        .section-card { 
            background: #fff; 
            border-radius: 10px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.08); 
            padding: 25px; 
            border: 1px solid #e9ecef;
        }
        .section-title { 
            font-size: 1.5rem; 
            font-weight: 600; 
            color: #0056b3; 
            border-bottom: 2px solid #f0f0f0; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
            display: flex;
            align-items: center;
        }
        .section-title i {
            margin-right: 12px;
        }
        
        /* Improved User List for Likers */
        .user-list {
            list-style-type: none;
            padding-left: 0;
        }
        .user-list li { 
            display: flex;
            align-items: center;
            padding: 10px 5px; 
            border-bottom: 1px solid #eee; 
            font-size: 1rem;
            color: #444;
        }
        .user-list li:last-child { border-bottom: none; }
        .user-list i {
            color: #007bff;
            margin-right: 10px;
        }
        
        /* Improved Comment Thread Styling */
        .comment-item { 
            padding: 15px 0; 
            border-bottom: 1px solid #eee; 
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .comment-item:last-child { border-bottom: none; margin-bottom: 0; }
        .comment-author { font-weight: bold; color: #333; font-size: 1.05rem; }
        .comment-content { color: #555; margin-top: 8px; font-style: italic; }
        .comment-date { font-size: 0.8rem; color: #888; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="interactions-container">
        <h1 class="page-title">Interactions for "{{ $post->title }}"</h1>

        <div class="interactions-grid">
            <!-- Likes Section -->
            <div class="section-card">
                <h2 class="section-title"><i class="fas fa-heart"></i> Likes ({{ count($likers) }})</h2>
                @if(count($likers) > 0)
                    <ul class="user-list">
                        @foreach($likers as $liker)
                            <li><i class="fas fa-user-circle"></i>{{ $liker->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No one has liked this post yet.</p>
                @endif
            </div>

            <!-- Comments Section -->
            <div class="section-card">
                <h2 class="section-title"><i class="fas fa-comments"></i> Comments ({{ count($commenters) }})</h2>
                @forelse($commenters as $comment)
                    <div class="comment-item">
                        <div class="comment-author">{{ $comment->name }}</div>
                        <p class="comment-content">"{{ $comment->content }}"</p>
                        <div class="comment-date">Commented on: {{ \Carbon\Carbon::parse($comment->created_at)->format('F j, Y, g:i a') }}</div>
                    </div>
                @empty
                    <p>No one has commented on this post yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    @include('home.footer')
</body>
</html>

