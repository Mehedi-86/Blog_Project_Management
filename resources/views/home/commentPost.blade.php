<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin-top: 40px;
            margin-bottom: 60px;
        }

        .header_section {
            position: relative;
            z-index: 1000;
        }

        h1.page-title {
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
            position: relative;
            display: block;
        }

        /* Decorative underline */
        h1.page-title::after {
            content: "";
            display: block;
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            margin: 12px auto 0;
            border-radius: 3px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            padding: 25px 30px;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-3px);
        }

        .card h4 {
            margin-bottom: 15px;
            color: #007bff;
            font-size: 1.6rem;
            font-weight: bold;
        }

        .card p {
            color: #444;
            font-size: 1.15rem;
            line-height: 1.6;
        }

        .btn-comment {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-comment:hover {
            background-color: #138496;
        }

        .btn-clear {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-clear:hover {
            background-color: #c82333;
        }

        .comment-input {
            flex: 1;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            font-size: 1rem;
        }

        .alert-success {
            text-align: center;
            margin-bottom: 25px;
        }

        .no-posts {
            text-align: center;
            margin-top: 50px;
            font-size: 1.3rem;
            font-weight: 500;
            color: #666;
        }

        /* Flex container for comment input + buttons */
        .comment-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .comment-row form {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="container">
        <h1 class="page-title">Comment on Posts</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($posts as $post)
            <div class="card">
                <h4>{{ $post->title }}</h4>
                <p>{{ $post->content }}</p>

                <!-- Comment Input + Buttons Row -->
                <div class="comment-row">
                    <!-- Comment Input & Submit -->
                    <form action="{{ route('commentPost', $post->id) }}" method="POST" style="flex:1; display:flex;">
                        @csrf
                        <input type="text" name="comment" class="comment-input" placeholder="Write your comment..." required>
                        <button type="submit" class="btn-comment" style="margin-left:5px;">💬 Comment</button>
                    </form>

                    <!-- Clear All Comments Button (if user has commented) -->
                    @if($post->user_commented)
                        <form action="{{ route('clearComments', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-clear"
                                onclick="return confirm('Are you sure you want to clear all comments for this post?');">
                                🗑️ Clear All Comments
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Existing Comments -->
                @if(isset($post->comments) && count($post->comments) > 0)
                    <div style="margin-top: 15px;">
                        <strong>Comments:</strong>
                        <ul>
                            @foreach($post->comments as $comment)
                                <li>{{ $comment->content }} 
                                    <small class="text-muted">by {{ $comment->username }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @empty
            <div class="no-posts">No posts available to comment!</div>
        @endforelse
    </div>

    @include('home.footer')
</body>
</html>
