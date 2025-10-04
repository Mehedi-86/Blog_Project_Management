<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      @include('home.homecss')

      <style>
         .analytics-container {
            padding: 50px 15px;
            background-color: #f4f7f6;
            font-family: 'Poppins', sans-serif;
         }
         .analytics-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
         }
         .table-responsive-container {
            overflow-x: auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            padding: 20px;
         }
         .analytics-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* Ensures table doesn't get too cramped on small screens before scrolling */
         }
         .analytics-table th, .analytics-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
         }
         .analytics-table th {
            font-size: 14px;
            font-weight: 600;
            color: #34495e;
            text-transform: uppercase;
            background-color: #f8f9fa;
         }
         .analytics-table tbody tr:hover {
            background-color: #f5f5f5;
         }
         .rank-badge {
            display: inline-block;
            width: 35px;
            height: 35px;
            line-height: 35px;
            border-radius: 50%;
            background-color: #3498db;
            color: #fff;
            text-align: center;
            font-weight: 700;
            font-size: 1rem;
         }
         /* Different colors for top ranks */
         .rank-badge-1 { background-color: #f1c40f; } /* Gold */
         .rank-badge-2 { background-color: #bdc3c7; } /* Silver */
         .rank-badge-3 { background-color: #cd7f32; } /* Bronze */

         .score {
            font-weight: 700;
            color: #27ae60;
            font-size: 1.1rem;
         }

         .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
         }
         .status-active {
            background-color: #e8f5e9;
            color: #2e7d32;
         }
         .status-rejected {
            background-color: #ffebee;
            color: #c62828;
         }
        
         .no-posts-message {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
         }
         .no-posts-message h3 {
            font-size: 1.5rem;
            color: #34495e;
            margin-bottom: 15px;
         }
         .no-posts-message p {
            color: #7f8c8d;
         }
         .no-posts-message .btn-add-post {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
         }
         .no-posts-message .btn-add-post:hover {
            background: #2980b9;
         }

      </style>
    </head>
   <body>
      <!-- header section start -->
      <div class="header_section">
        @include('home.header')
      </div>
      <!-- header section end -->
      
      <div class="analytics-container">
         <h1 class="analytics-title">My Post Performance</h1>

         <div class="table-responsive-container">
            @forelse ($posts as $post)
               @if ($loop->first)
                  <table class="analytics-table">
                     <thead>
                        <tr>
                           <th>Rank</th>
                           <th>Post Title</th>
                           <th>Status</th>
                           <th>Views</th>
                           <th>❤️ Likes</th>
                           <th>💬 Comments</th>
                           <th>💾 Saves</th>
                           <th>Engagement Score</th>
                        </tr>
                     </thead>
                     <tbody>
               @endif
                     <tr>
                        <td>
                           <span class="rank-badge rank-badge-{{ $post->performance_rank <= 3 ? $post->performance_rank : '' }}">
                              {{ $post->performance_rank }}
                           </span>
                        </td>
                        <td>{{ $post->title }}</td>
                        <td>
                           <span class="status status-{{ strtolower($post->status) }}">
                              {{ $post->status }}
                           </span>
                        </td>
                        <td>{{ $post->views }}</td>
                        <td>{{ $post->like_count }}</td>
                        <td>{{ $post->comment_count }}</td>
                        <td>{{ $post->save_count }}</td>
                        <td class="score">{{ $post->engagement_score }}</td>
                     </tr>
               @if ($loop->last)
                     </tbody>
                  </table>
               @endif
            @empty
               <div class="no-posts-message">
                  <h3>You haven't created any posts yet.</h3>
                  <p>Start sharing your content to see your analytics here!</p>
                  <a href="{{ route('addPost') }}" class="btn-add-post">Create Your First Post</a>
               </div>
            @endforelse
         </div>
      </div>
      
      @include('home.footer')
      
   </body>
</html>
