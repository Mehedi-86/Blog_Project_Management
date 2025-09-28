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
            background: linear-gradient(90deg, #ff758c 0%, #ff7eb3 100%);
            color: #fff;
            font-weight: 600;
        }

        .posts-table tbody tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        .posts-table tbody tr:hover {
            background-color: #ffe4ec;
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

    <div class="table-title">Posts Reported By Me</div>
    <table class="posts-table">
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Post Owner (Name & Email)</th>
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
                <td>{{ $report->post_owner_name }} ({{ $report->post_owner_email }})</td>
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
