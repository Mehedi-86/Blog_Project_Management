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
        }
        .header_section {
            position: relative;
            z-index: 1000;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            padding: 25px 30px;
            transition: transform 0.2s ease-in-out;
            display: flex;
            flex-direction: column;
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

        .btn-like, .btn-save, .btn-report {
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            transition: background 0.3s ease;
            margin-right: 10px;
        }

        .btn-like { background-color: #dc3545; }
        .btn-like:hover { background-color: #c82333; }

        .btn-save { background-color: #17a2b8; }
        .btn-save:hover { background-color: #138496; }

        .btn-report { background-color: #fd7e14; }
        .btn-report:hover { opacity: 0.85; }

        .btn-container { display: flex; margin-top: 15px; }

        .no-posts {
            text-align: center;
            margin-top: 50px;
            font-size: 1.3rem;
            font-weight: 500;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="container mt-4 mb-5">
        <h1 class="page-title">Available Posts</h1>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        @foreach($posts as $post)
            @php
                $reported = DB::table('reports')
                    ->where('post_id', $post->id)
                    ->where('reported_by', auth()->id())
                    ->exists();
            @endphp

            <div class="card">
                <h4>{{ $post->title }}</h4>
                
                <small>
                    📌 Post by <strong>{{ $post->name }}</strong> 
                    | 🏷️ Category: <strong>{{ $post->category_name ?? 'Uncategorized' }}</strong>
                </small>

                <div class="btn-container">
                    <!-- Like / Unlike -->
                    @if(in_array($post->id, $liked))
                        <form action="{{ route('unlikePost', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-like" style="background-color:#6c757d;">💔 Unlike</button>
                        </form>
                    @else
                        <form action="{{ route('likePost', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-like">❤️ Like</button>
                        </form>
                    @endif

                    <!-- Save / Unsave -->
                    @if(in_array($post->id, $saved))
                        <form action="{{ route('unsavePost', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-save" style="background-color:#6c757d;">💾 Unsave</button>
                        </form>
                    @else
                        <form action="{{ route('savePost', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-save">💾 Save</button>
                        </form>
                    @endif

                    <!-- Report / Undo Report -->
                    @if($reported)
                        <form action="{{ route('undoReportPost', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-report">⚠️ Undo Report</button>
                        </form>
                    @else
                        <form action="{{ route('reportPost', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-report">⚠️ Report</button>
                        </form>
                    @endif

                    <!-- View Button with Count -->
                    <a href="{{ route('viewPost', $post->id) }}" 
                        class="btn btn-info"
                        style="border-radius:10px; padding:10px 20px; font-weight:600; color:white;">
                        👁️ View ({{ $post->views }})
                    </a>

                    <!-- Update & Delete Buttons (only visible to post owner) -->
                    @if(Auth::id() === $post->user_id)
                        <div style="display: flex; gap: 10px;">
                            <!-- Update Button -->
                            <a href="{{ route('editPost', $post->id) }}" 
                                class="btn btn-warning" 
                                style="border-radius:10px; padding:10px 20px; font-weight:600; color:white;">
                                ✏️ Update
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('deletePost', $post->id) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        style="border-radius:10px; padding:10px 20px; font-weight:600;">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if(count($posts) === 0)
            <div class="no-posts">No posts available!</div>
        @endif
    </div>

    @include('home.footer')
</body>
</html>
