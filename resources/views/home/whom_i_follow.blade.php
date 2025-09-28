<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')

    <style>
        .followings-table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .followings-table th, .followings-table td {
            padding: 12px 20px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .followings-table thead {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            font-weight: 600;
        }

        .followings-table tbody tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        .followings-table tbody tr:hover {
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
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="table-title">Whom I Follow</div>
    <table class="followings-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>User Type</th>
                <th>Followed At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($followings as $following)
            <tr>
                <td>{{ $following->id }}</td>
                <td>{{ $following->name }}</td>
                <td>{{ $following->email }}</td>
                <td>{{ $following->phone }}</td>
                <td>{{ ucfirst($following->usertype) }}</td>
                <td>{{ $following->followed_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @include('home.footer')
</body>
</html>
