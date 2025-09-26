<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header_section {
            margin: 0;
            padding: 0;
        }

        .main-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 15px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
        }

        .card-header {
            background: #007bff;
            color: white;
            border-radius: 12px 12px 0 0;
            font-size: 1.4rem;
            font-weight: bold;
            padding: 15px;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .btn-success {
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- header section -->
    <div class="header_section">
        @include('home.header')
    </div>

    <!-- Add Post Form -->
    <div class="main-container">
        <div class="card">
            <div class="card-header">Add New Post</div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('addPost.store') }}" method="POST">
                    @csrf
                    <input type="text" name="title" class="form-control" placeholder="Post Title" required>
                    <textarea name="content" class="form-control" rows="5" placeholder="Post Content" required></textarea>

                    <!-- Category Dropdown -->
                    <select name="category_id" class="form-control" required>
                        <option value="" disabled selected>-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success mt-3">➕ Add Post</button>
                </form>
            </div>
        </div>
    </div>

    @include('home.footer')
</body>
</html>
