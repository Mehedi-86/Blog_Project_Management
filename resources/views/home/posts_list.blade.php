<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic CSS -->
    @include('home.homecss')

    <style>
        /* Posts Table Styling */
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
    </style>
</head>
<body>
    <!-- header section start -->
    <div class="header_section">
        @include('home.header')
    </div>
    <!-- header section end -->

    <!-- Posts Table -->
    <div class="table-title">All Posts</div>
    <table class="posts-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Title</th>
                <th>Views</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->name }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->views }}</td>
                <td>{{ $post->category_name ?? 'Uncategorized' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- footer section -->
    @include('home.footer')
</body>
</html>
