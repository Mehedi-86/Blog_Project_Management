<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')

    <style>
        .reports-table {
            width: 95%;
            margin: 40px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .reports-table th, .reports-table td {
            padding: 12px 18px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .reports-table thead {
            background: linear-gradient(90deg, #ff6a00 0%, #ee0979 100%);
            color: #fff;
            font-weight: 600;
        }

        .reports-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .reports-table tbody tr:hover {
            background-color: #ffe6e6;
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

    <div class="table-title">Who Reported On My Posts</div>
    <table class="reports-table">
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Reported By</th>
                <th>Email</th>
                <th>Post ID</th>
                <th>Post Title</th>
                <th>Reason</th>
                <th>Reported At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->report_id }}</td>
                <td>{{ $report->reporter_name }}</td>
                <td>{{ $report->reporter_email }}</td>
                <td>{{ $report->post_id }}</td>
                <td>{{ $report->post_title }}</td>
                <td>{{ $report->reason }}</td>
                <td>{{ $report->reported_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @include('home.footer')
</body>
</html>
