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
        }

        .posts-table thead {
            background: linear-gradient(90deg, #ff4e50 0%, #f9d423 100%);
            color: #fff;
            font-weight: 600;
        }

        .posts-table tbody tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        .posts-table tbody tr:hover {
            background-color: #ffe0e0;
            transition: background-color 0.3s;
        }

        .table-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #b30000;
        }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="table-title">Rejected Posts</div>
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
                <td>{{ $post->title }}</td>
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
