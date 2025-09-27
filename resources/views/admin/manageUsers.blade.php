<!DOCTYPE html>
<html>
<head>
    @include('admin.css')
    <style>
        .title_deg { font-size: 32px; font-weight: 700; color: #fff;
            background: linear-gradient(to right, #0D47A1, #1976D2);
            padding: 10px; text-align: center; border-radius: 10px; margin-bottom: 30px; }
        .table_deg { width: 95%; margin: 0 auto; border-collapse: collapse; border-radius: 10px; overflow: hidden; }
        .table_deg th, .table_deg td { text-align: center; padding: 12px; }
        .table_deg thead { background-color: #0D47A1; color: #fff; }
        .table_deg tbody tr:nth-child(odd) { background-color: #141414; }
        .table_deg tbody tr:nth-child(even) { background-color: #0f1b2a; }
    </style>
</head>
<body>
@include('admin.header')
<div class="d-flex align-items-stretch">
    @include('admin.sidebar')

    <div class="page-content">
        <h1 class="title_deg">Manage Users</h1>

        @if(session()->has('message'))
            <div class="alert alert-{{ session()->get('type', 'info') }} alert-dismissible fade show">
                {{ session()->get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert">X</button>
            </div>
        @endif

        <table class="table_deg table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Ban</th>
                    <th>Unban</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->usertype }}</td>
                    <td>
                        @if($user->is_banned == 0)
                            <a href="{{ route('admin.ban.user', $user->id) }}" class="btn btn-danger">Ban</a>
                        @else
                            <span class="text-warning">Banned</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_banned == 1)
                            <a href="{{ route('admin.unban.user', $user->id) }}" class="btn btn-success">Unban</a>
                        @else
                            <span class="text-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.delete.user', $user->id) }}" class="btn btn-secondary" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($users) === 0)
            <div class="text-center mt-4">No users available!</div>
        @endif
    </div>
</div>
@include('admin.footer')
<script src="admincss/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
