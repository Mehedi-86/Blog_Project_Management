<!DOCTYPE html>
<html lang="en">
   <head>
      @include('home.homecss')

      <!-- Include Chart.js from a CDN -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <style>
         .analysis-container {
            padding: 50px 15px;
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
         }
         .analysis-card {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
         }
         .analysis-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.2rem;
            font-weight: 700;
            color: #34495e;
         }
      </style>
   </head>

   <body>
      <!-- header section start -->
      <div class="header_section">
         @include('home.header')
      </div>
      <!-- header section end -->

      <div class="analysis-container">
         <div class="analysis-card">
            <h1 class="analysis-title">Your Activity Analysis (Last 14 Days)</h1>

            @if(empty($chartLabels))
               <p style="text-align:center; color:gray;">No activity data available for the past 14 days.</p>
            @else
               <!-- Canvas for the chart -->
               <canvas id="activityChart"></canvas>
            @endif
         </div>
      </div>

      @include('home.footer')

      <script>
         // Safely convert PHP arrays to JS
         const labels = @json($chartLabels ?? []);
         const actionData = @json($chartActionData ?? []);
         const durationData = @json($chartDurationData ?? []);

         // Only initialize chart if we have data
         if (labels.length > 0) {
            const data = {
               labels: labels,
               datasets: [
                  {
                     label: 'Actions Per Day',
                     data: actionData,
                     backgroundColor: 'rgba(54, 162, 235, 0.6)',
                     borderColor: 'rgba(54, 162, 235, 1)',
                     borderWidth: 1,
                     type: 'bar',
                     yAxisID: 'y',
                     order: 2
                  },
                  {
                     label: 'Session Duration (Minutes)',
                     data: durationData,
                     backgroundColor: 'rgba(255, 99, 132, 0.8)',
                     borderColor: 'rgba(255, 99, 132, 1)',
                     tension: 0.3,
                     type: 'line',
                     yAxisID: 'y1',
                     order: 1
                  }
               ]
            };

            const config = {
               data: data,
               options: {
                  responsive: true,
                  interaction: { mode: 'index', intersect: false },
                  scales: {
                     x: {
                        display: true,
                        title: { display: true, text: 'Date' }
                     },
                     y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Total Actions' },
                        grid: { drawOnChartArea: false }
                     },
                     y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Duration (Minutes)' }
                     }
                  }
               }
            };

            new Chart(document.getElementById('activityChart'), config);
         }
      </script>
   </body>
</html>


<!--
Activity Analysis Graph Explanation:

1️. X-axis (Horizontal) – Dates
- Shows the last 14 consecutive days.
- Even days with 0 activity are included, so the timeline is consistent.
- Example labels: Sep 26, Sep 27, …

2️. Left Y-axis – Total Actions (Bars)
- Represents the number of actions you performed that day.
- Actions include: creating posts, liking posts, commenting, saving posts.
- The height of each blue bar shows how active you were.
- Example:
  - Bar height = 14 → 14 actions performed
  - Bar height = 0 → No actions that day

3️. Right Y-axis – Session Duration (Line)
- Represents how long you were active in a day, based on first and last actions.
- Calculated as time difference between first and last activity in minutes.
- The red line rises when you spend more time on the site, dips when less.
- Example:
  - 201 minutes → ~3 hours 20 minutes
  - 0 minutes → Either one action or no activity that day

4️. How to interpret trends
- High bar + high line → Very active day, spent a long time
- High bar + low line → Many actions quickly (short session)
- Low bar + high line → Few actions spread over a long period
- Zero bar + zero line → No activity

5️. Extra insights
- Helps track streaks: consecutive days with activity
- Shows gaps: when you didn’t log in or interact
- Can guide you to increase engagement or optimize posting times

-->
