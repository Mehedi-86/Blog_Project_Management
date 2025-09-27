<!DOCTYPE html>
<html>
<head> 
    @include('admin.css')

    <!-- SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" 
            integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" 
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        .title_deg {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(to right, #6A0DAD, #8E24AA);
            padding: 10px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .table_deg {
            width: 95%;
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .table_deg th, .table_deg td {
            text-align: center;
            padding: 12px 15px;
            vertical-align: middle;
        }
        .table_deg thead {
            background-color: #6A0DAD;
            color: #fff;
        }
        .table_deg tbody tr:nth-child(odd) { background-color: #141414; }
        .table_deg tbody tr:nth-child(even) { background-color: #0f1b2a; }
        .table_deg tbody tr:hover { color: #fff; cursor: pointer; }
        .btn { padding: 6px 14px; font-size: 14px; border-radius: 8px; font-weight: 500; cursor: pointer; border: 1px solid transparent; transition: all 0.3s ease, transform 0.2s ease; }
        .btn:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); }
        .btn-success { background-color: #28a745; color: #fff; border-color: #28a745; }
        .btn-success:hover { background-color: #218838; border-color: #1e7e34; }
        .btn-danger { background-color: #dc3545; color: #fff; border-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; border-color: #bd2130; }
        .btn-secondary { background-color: #6c757d; color: #fff; border-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; border-color: #545b62; }
        .btn-primary { background-color: #007bff; color: #fff; border-color: #007bff; }
        .btn-primary:hover { background-color: #0056b3; border-color: #004085; }
    </style>
</head>

<body>
    @include('admin.header')

    <div class="d-flex align-items-stretch">
        @include('admin.sidebar')

        <div class="page-content">
            <h1 class="title_deg">Manage Posts</h1>
            
            @if(session()->has('message'))
                <div class="alert alert-{{ session()->get('type', 'info') }} alert-dismissible fade show" role="alert">
                    {{ session()->get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
                </div>
            @endif

            <table class="table_deg table table-bordered">
                <thead>
                    <tr>
                        <th>Post Title</th>
                        <th>Posted By</th>
                        <th>Status</th>
                        <th>User Type</th>
                        <th>Delete</th>
                        <th>Accept</th>
                        <th>Reject</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->name }}</td>
                        <td>{{ $post->status }}</td> {{-- Correct column --}}
                        <td>{{ $post->usertype }}</td>

                        <td>
                            <a href="{{ route('admin.delete.post', $post->id) }}" class="btn btn-danger" onclick="confirmation(event)">
                                <strong>Delete</strong>
                            </a>
                        </td>
                        <td>
                            @if($post->status != 'active')
                                <a href="{{ route('admin.accept.post', $post->id) }}" class="btn btn-success" onclick="return confirm('Are you sure to accept this post?')">
                                    <strong>Accept</strong>
                                </a>
                            @else
                                <span class="text-success">Active</span>
                            @endif
                        </td>
                        <td>
                            @if($post->status != 'rejected')
                                <a href="{{ route('admin.reject.post', $post->id) }}" class="btn btn-primary" onclick="return confirm('Are you sure to reject this post?')">
                                    <strong>Reject</strong>
                                </a>
                            @else
                                <span class="text-warning">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($posts) === 0)
                <div class="text-center mt-4">No posts available!</div>
            @endif
        </div>
    </div>

    @include('admin.footer')

    <script>
        function confirmation(ev) {
            ev.preventDefault();
            var urlToRedirect = ev.currentTarget.getAttribute('href');
            swal({
                title: "Are you sure to delete this post?",
                text: "You won't be able to revert this!",
                icon: "warning",
                buttons: ["Cancel", "Delete"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.href = urlToRedirect;
                }
            });
        }
    </script>
    <script src="admincss/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
