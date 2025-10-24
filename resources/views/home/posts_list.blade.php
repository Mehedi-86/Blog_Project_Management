<!DOCTYPE html>
<html lang="en">
<head>
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
            cursor: pointer;
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

    <!-- Table Title -->
    <div class="table-title">All Posts</div>

    <!-- Category Filter Form -->
    <form method="GET" action="{{ route('posts.list') }}" id="category-filter-form" style="text-align:center; margin-bottom:20px;">
        <label style="margin-right:10px; font-weight:600;">Filter by Category:</label>

        <div class="custom-select-wrapper" style="display:inline-block; position:relative; width:220px;">
            <input type="hidden" name="category_id" id="category_id" value="{{ $categoryId ?? '' }}">

            <div class="custom-select" style="border:1px solid #ccc; border-radius:6px; background:#fff; cursor:pointer; padding:10px 12px; user-select:none;">
                <span id="selected-category">
                    @php
                        $selectedCategoryName = 'All Categories';
                        if(isset($categoryId) && $categoryId) {
                            foreach($categories as $category) {
                                if($category->id == $categoryId) {
                                    $selectedCategoryName = $category->name;
                                    break;
                                }
                            }
                        }
                        echo e($selectedCategoryName);
                    @endphp
                </span>
                <span style="float:right;">&#9662;</span>
            </div>

            <div class="custom-options" style="display:none; position:absolute; top:100%; left:0; right:0; border:1px solid #ccc; border-radius:6px; background:#fff; z-index:1000; max-height:200px; overflow-y:auto;">
                <span class="custom-option" data-value="">All Categories</span>
                @foreach($categories as $category)
                    <span class="custom-option" data-value="{{ $category->id }}" style="display:block; padding:8px 12px; cursor:pointer;">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </form>

    <!-- Posts Table -->
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
            <tr onclick="window.location='{{ route('post.details', $post->id) }}';">
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.querySelector('.custom-select-wrapper');
        const trigger = wrapper.querySelector('.custom-select');
        const optionsContainer = wrapper.querySelector('.custom-options');
        const options = wrapper.querySelectorAll('.custom-option');
        const hiddenInput = wrapper.querySelector('#category_id');
        const form = document.querySelector('#category-filter-form');
        const selectedSpan = wrapper.querySelector('#selected-category');

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            optionsContainer.style.display = optionsContainer.style.display === 'block' ? 'none' : 'block';
        });

        options.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                selectedSpan.textContent = this.textContent;
                hiddenInput.value = value;
                optionsContainer.style.display = 'none';
                form.submit();
            });
        });

        window.addEventListener('click', function() {
            optionsContainer.style.display = 'none';
        });
    });
    </script>
</body>
</html>
