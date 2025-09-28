<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')

    <style>
        .posts-table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .posts-table th, .posts-table td {
            padding: 12px 20px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle; /* Ensures content is vertically centered */
        }

        .posts-table thead {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            font-weight: 600;
        }

        .posts-table tbody tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        .posts-table tbody tr:hover {
            background-color: #d1f0ff;
            transition: background-color 0.3s;
        }

        .table-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        /* --- New Badge Styling --- */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 0.75rem; /* Smaller font size */
            font-weight: 700;
            color: #fff;
            background-color: #6c757d; /* Default color */
            border-radius: 12px; /* Pill shape */
            margin-left: 8px; /* Space between title and badge */
        }
        
        /* Specific colors for different statuses */
        .badge-hot { background-color: #dc3545; } /* Red for Hot */
        .badge-popular { background-color: #28a745; } /* Green for Popular */
        .badge-new { background-color: #17a2b8; } /* Blue for New */

    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="table-title">Active Posts</div>
    <table class="posts-table">
            <thead>
            <tr>
                <th>Post ID</th>
                <th>Post Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Views</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <!-- This is the updated table cell -->
                <td>
                    {{ $post->title }}
                    
                    @php
                        // Determine badge class based on the status from your query
                        $badgeClass = '';
                        if ($post->popularity_status === '🔥 Hot') {
                            $badgeClass = 'badge-hot';
                        } elseif ($post->popularity_status === '👍 Popular') {
                            $badgeClass = 'badge-popular';
                        } elseif ($post->popularity_status === '✨ New') {
                            $badgeClass = 'badge-new';
                        }
                    @endphp

                    <span class="badge {{ $badgeClass }}">{{ $post->popularity_status }}</span>
                </td>
                <td>{{ $post->category_name ?? 'N/A' }}</td>
                <td>{{ $post->author_name }}</td>
                <td>{{ $post->views }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @include('home.footer')
</body>
</html>
