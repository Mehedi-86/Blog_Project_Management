<style>
    /* --- Styles for Leaderboard Component --- */
    .leaderboard-container {
        width: 90%;
        max-width: 900px;
        margin: auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .leaderboard-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 40px;
    }

    .category-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .category-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #0056b3;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .post-list {
        list-style: none;
        padding-left: 0;
    }

    .post-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 10px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 1.1rem;
    }

    .post-item:last-child {
        border-bottom: none;
    }
    
    .post-rank {
        font-weight: bold;
        font-size: 1.2rem;
        margin-right: 20px;
        color: #555;
        min-width: 40px;
    }
    
    .post-title {
        flex-grow: 1;
        color: #333;
    }

    .post-views {
        font-weight: 600;
        color: #28a745;
        background-color: #e9f5ec;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.9rem;
    }

    .no-posts-message {
        text-align: center;
        padding: 50px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<div class="leaderboard-container">
    <h1 class="leaderboard-title">🏆 Top Posts Leaderboard</h1>

    {{-- This PHP block groups the flat array from the controller 
         into a structured array where each key is a category name. --}}
    @php
        $groupedPosts = [];
        // Check if $topPosts is passed to avoid errors
        if (isset($topPosts)) {
            foreach ($topPosts as $post) {
                // Use null coalescing for uncategorized posts
                $groupedPosts[$post->category_name ?? 'Uncategorized'][] = $post;
            }
        }
    @endphp

    {{-- Now we loop through the new, structured array. 
         @forelse handles the case where there are no posts. --}}
    @forelse ($groupedPosts as $categoryName => $postsInCategory)
        <div class="category-card">
            <h2 class="category-title">{{ $categoryName }}</h2>
            <ul class="post-list">
                @foreach ($postsInCategory as $post)
                    <li class="post-item">
                        <span class="post-rank">
                            {{-- Assign medals based on rank --}}
                            @if ($post->rn == 1) 🥇
                            @elseif ($post->rn == 2) 🥈
                            @elseif ($post->rn == 3) 🥉
                            @endif
                        </span>
                        <span class="post-title">{{ $post->title }}</span>
                        <span class="post-views">{{ $post->views }} Views</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <div class="no-posts-message">
            <h3>No active posts found to rank.</h3>
            <p>Once posts get more views, they will appear here!</p>
        </div>
    @endforelse
</div>

