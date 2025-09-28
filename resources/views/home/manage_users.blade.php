<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')

    <style>
        .users-table {
            width: 90%;
            margin: 20px auto 40px;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .users-table th, .users-table td {
            padding: 12px 20px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .users-table thead {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            font-weight: 600;
        }

        .users-table tbody tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        .users-table tbody tr:hover {
            background-color: #d1f0ff;
            transition: background-color 0.3s;
        }

        .table-title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <!-- Active Users Table -->
    <div class="table-title">Active Users</div>
    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>User Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activeUsers as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->usertype }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Banned Users Table -->
    <div class="table-title">Banned Users</div>
    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>User Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bannedUsers as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->usertype }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @include('home.footer')
</body>
</html>
