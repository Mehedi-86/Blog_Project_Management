<!DOCTYPE html>
<html>
  <head>
    @include('admin.css')

    {{-- Custom styles for the analytics dashboard cards --}}
    <style>
        .page-content {
            background-color: #1a222e; /* Match the dark theme */
            width: 100%;
            padding: 25px;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        .dashboard-title {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 40px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
        }
        .card {
            background: #0f1b2a; /* Dark card background */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            padding: 20px;
            border: 1px solid #333;
        }
        .card h3 {
            margin-top: 0;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            color: #eee;
            font-size: 1.3rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            color: #ccc;
        }
        th, td {
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        th {
            font-weight: 600;
            color: #fff;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
    </style>
  </head>
  <body>
    @include('admin.header')
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      @include('admin.sidebar')
      <!-- Sidebar Navigation end-->

      <!-- Main Page Content -->
      <div class="page-content">
        <div class="dashboard-container">
            <h1 class="dashboard-title">📈 Analytics Dashboard</h1>

            <div class="dashboard-grid">

                <!-- Card for Daily Posts -->
                <div class="card">
                    <h3>New Posts (Last 7 Days)</h3>
                    <table>
                        <thead><tr><th>Date</th><th>Posts Created</th></tr></thead>
                        <tbody>
                            @forelse($postsLast7Days as $row)
                                <tr><td>{{ $row->creation_date }}</td><td>{{ $row->post_count }}</td></tr>
                            @empty
                                <tr><td colspan="2">No new posts in the last 7 days.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Card for Power Users -->
                <div class="card">
                    <h3>Power Users (> 3 Posts)</h3>
                    <table>
                        <thead><tr><th>Name</th><th>Total Posts</th></tr></thead>
                        <tbody>
                            @forelse($powerUsers as $user)
                                <tr><td>{{ $user->name }}</td><td>{{ $user->total_posts }}</td></tr>
                            @empty
                                <tr><td colspan="2">No users have posted more than 3 times.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Card for Activity Log -->
                <div class="card">
                    <h3>Recent Activity Log</h3>
                    <table>
                        <thead><tr><th>Activity</th><th>Details</th><th>Time</th></tr></thead>
                        <tbody>
                            @forelse($activityLog as $log)
                                <tr>
                                    <td>{{ $log->activity_type }}</td>
                                    <td>{{ Str::limit($log->details, 25) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No recent activity.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
      </div>
      <!-- Main Page Content End -->

    </div>

    {{-- The footer in your template seems to be outside the main content flow, so keeping it here. --}}
    <footer class="footer">
      <div class="footer__block block no-margin-bottom">
        <div class="container-fluid text-center">
           <p class="no-margin-bottom">2025 &copy; Your Company. All Rights Reserved.</p>
        </div>
      </div>
    </footer>

    <!-- JavaScript files-->
    <script src="{{asset('admincss/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('admincss/vendor/popper.js/umd/popper.min.js')}}"> </script>
    <script src="{{asset('admincss/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('admincss/vendor/jquery.cookie/jquery.cookie.js')}}"> </script>
    <script src="{{asset('admincss/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('admincss/vendor/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('admincss/js/charts-home.js')}}"></script>
    <script src="{{asset('admincss/js/front.js')}}"></script>
  </body>
</html>

